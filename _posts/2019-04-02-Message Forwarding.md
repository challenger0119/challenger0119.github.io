##  消息转发

1. `resolveInstanceMethod:` 动态添加方法实现的地方
2. `forwardingTargetForMethod:` 消息转发
3. `methodSignatureForSelector:`  获取方法签名
4. `forwardingInvocation:` 转发NSInvocation

 

三个方法的调用顺序：

```objective-c
// 例子： SandboxFiles 类下有 test 方法没有实现  重写一下三个方法

+ (BOOL)resolveInstanceMethod:(SEL)sel{
    NSLog(@"resolveInstanceMethod %@", NSStringFromSelector(sel));
    return [super resolveInstanceMethod:sel];
}

- (id)forwardingTargetForSelector:(SEL)aSelector{
    NSLog(@"forwardingTargetForSelector %@", NSStringFromSelector(aSelector));
    return [super forwardingTargetForSelector:aSelector];
}

- (NSMethodSignature *)methodSignatureForSelector:(SEL)aSelector{
    NSLog(@"methodSignatureForSelector %@",NSStringFromSelector(aSelector));
    return [super methodSignatureForSelector:aSelector];
}

// 执行 test 后

/*
2018-10-27 23:18:52.845763+0800 FilePre[2210:59678] resolveInstanceMethod test
2018-10-27 23:18:52.845930+0800 FilePre[2210:59678] forwardingTargetForSelector test
2018-10-27 23:18:52.846041+0800 FilePre[2210:59678] methodSignatureForSelector test
2018-10-27 23:18:52.846179+0800 FilePre[2210:59678] resolveInstanceMethod test
2018-10-27 23:18:52.846299+0800 FilePre[2210:59678] -[SandboxFiles test]: unrecognized selector sent to instance 0x600001d22910
*/
```

#### resolveInstanceMethod   

>This method and [`resolveClassMethod(_:)`](https://developer.apple.com/documentation/objectivec/nsobject/1418889-resolveclassmethod) allow you to dynamically provide an implementation for a given selector.
>
>An Objective-C method is simply a C function that take at least two arguments—`self` and `_cmd`. Using the [`class_addMethod(_:_:_:_:)`](https://developer.apple.com/documentation/objectivec/1418901-class_addmethod) function, you can add a function to a class as a method. 
>
>This method is called before the Objective-C forwarding mechanism is invoked. If [`responds(to:)`](https://developer.apple.com/documentation/objectivec/nsobjectprotocol/1418583-responds) or [`instancesRespond(to:)`](https://developer.apple.com/documentation/objectivec/nsobject/1418555-instancesrespond) is invoked, the dynamic method resolver is given the opportunity to provide an `IMP` for the given selector first.

```objective-c
// 动态添加方法

void dynamicTestMethod(id self,SEL _cmd){
    NSLog(@"dynamicTestMethod %p selector:%@",self, NSStringFromSelector(_cmd));
}

+ (BOOL)resolveInstanceMethod:(SEL)sel{
    NSLog(@"resolveInstanceMethod %@", NSStringFromSelector(sel));
    if (sel == @selector(test)) {
        NSLog(@"resolveInstanceMethod add method %@",NSStringFromSelector(sel));
        class_addMethod([self class], sel, (IMP)dynamicTestMethod, "@v@:");
        return YES;
    }
    return [super resolveInstanceMethod:sel];
}

// 运行结果

/*
2018-10-27 23:20:28.905674+0800 FilePre[2241:60668] resolveInstanceMethod test
2018-10-27 23:20:28.905843+0800 FilePre[2241:60668] resolveInstanceMethod add method test
2018-10-27 23:20:28.905971+0800 FilePre[2241:60668] dynamicTestMethod 0x600002638b60 selector:test
*/
```

#### forwardingTargetForSelector

> Returns the object to which unrecognized messages should first be directed.
>
> This method gives an object a chance to redirect an unknown message sent to it before the much more expensive [`forwardInvocation:`](https://developer.apple.com/documentation/objectivec/nsobject/1571955-forwardinvocation?language=objc) machinery takes over. 
>
> Obviously if you return `self` from this method, the code would just fall into an infinite loop.

```objective-c
// AnotherTarget 实现了 test

- (id)forwardingTargetForSelector:(SEL)aSelector{
    NSLog(@"forwardingTargetForSelector %@", NSStringFromSelector(aSelector));
    AnotherTarget *obj = [AnotherTarget new];
    if ([obj respondsToSelector:aSelector]) {
        return obj;
    }else{
        return [super forwardingTargetForSelector:aSelector];
    }
}

// 打印：
/*
2018-10-28 12:36:31.644226+0800 FilePre[2573:77914] resolveInstanceMethod test
2018-10-28 12:36:31.644405+0800 FilePre[2573:77914] forwardingTargetForSelector test
2018-10-28 12:36:31.644501+0800 FilePre[2573:77914] AnotherTarget test
*/
```

#### methodSignatureForSelector:

> Raises `NSInvalidArgumentException`. Override this method in your concrete subclass to return a proper `NSMethodSignature` object for the given selector and the class your proxy objects stand in for.

#### forwardingInvocation:

> When an object is sent a message for which it has no corresponding method, the runtime system gives the receiver an opportunity to delegate the message to another receiver. It delegates the message by creating an `NSInvocation` object representing the message and sending the receiver a `forwardInvocation:` message containing this `NSInvocation`object as the argument. The receiver’s `forwardInvocation:` method can then choose to forward the message to another object. (If that object can’t respond to the message either, it too will be given a chance to forward it.)

```objective-c

// 获取AnotherTarget 的 test 方法的签名 触发转发
- (NSMethodSignature *)methodSignatureForSelector:(SEL)aSelector{
    return [[AnotherTarget new] methodSignatureForSelector:aSelector];
}

// 在AnotherTarget上invoke
- (void)forwardInvocation:(NSInvocation *)anInvocation{
    SEL selector = [anInvocation selector];
    
    NSLog(@"forwardInvocation %@", NSStringFromSelector(selector));

    AnotherTarget *obj = [AnotherTarget new];
    if ([obj respondsToSelector:selector]) {
        [anInvocation invokeWithTarget:obj];
    }else{
        [super forwardInvocation:anInvocation];
    }
}

/*
2018-10-28 13:31:38.556762+0800 FilePre[3088:100888] resolveInstanceMethod test
2018-10-28 13:31:38.556976+0800 FilePre[3088:100888] forwardingTargetForSelector test
2018-10-28 13:31:38.557142+0800 FilePre[3088:100888] resolveInstanceMethod _forwardStackInvocation:
2018-10-28 13:31:38.557264+0800 FilePre[3088:100888] forwardInvocation test
2018-10-28 13:31:38.557354+0800 FilePre[3088:100888] AnotherTarget test
*/
```

