### Block 结构体

## [参考1](https://www.jianshu.com/p/ee9756f3d5f6) [参考2](http://www.galloway.me.uk/2013/05/a-look-inside-blocks-episode-3-block-copy/)  [参考3](https://blog.devtang.com/2013/07/28/a-look-inside-blocks/)


   ```objective-c
   // 1. Aspects 对Block的声明
   
   typedef NS_OPTIONS(int, AspectBlockFlags) {
   	AspectBlockFlagsHasCopyDisposeHelpers = (1 << 25),
   	AspectBlockFlagsHasSignature          = (1 << 30)
   };
   
   typedef struct _AspectBlock{
       __unused Class isa;		
       AspectBlockFlag flags; // int型 位 标志 Block是否有：1.signature 2.copyDisposeHelper
       __unused int reserved;
       void (__unused *invoke)(struct _AspectBlock *block, ...); 
       struct{
           unsigned long int reserved;
           unsigned long int size;	
           
           // 下面这两部分根据具体情况 可能都有也可能都没有 
           //辅助（helper）方法，在block copy的时候做一些事情，例如维护下捕获的外部属性
           // AspectBlockFlagsHasCopyDisposeHelpers 
           void (*copy)(void *dst, void *src);
           void (*dispose)(const void *);
           // AspectBlockFlagsHasSignature
           const char *signature;
           const char *layout;
       } *descriptor;
       
       // 后面还会有import variable 也就是Block捕获的外部变量
   } *AspectBlockRef;
   
   
   
   
   // 2. Block 的C剖析
   
   // 2.1.1 没有捕获变量的时候或者变量不需要修改 时候的descriptor
   struct Block_descriptor {
       unsigned long int reserved;
       unsigned long int size;	// Block的大小 包括捕获变量部分
   };
   
   // 2.1.2 捕获了需要修改的变量时候的descriptor
   struct Block_descriptor {
       unsigned long int reserved;
       unsigned long int size;
       void (*copy)(void *dst, void *src);
       void (*dispose)(void *);
   };
   
   // 2.2 block 结构体
   struct Block_layout {
       void *isa;		// 对象指针 三种
       int flags;		// 附加信息 如上
       int reserved;
       void (*invoke)(void *, ...);	
       struct Block_descriptor *descriptor;
       /* Imported variables. */
       // 捕获的变量在这里
   };
   
   // isa Block对象类型：
   // 全局Block _NSConcreteGlobalBlock
   // 栈Block _NSConcreteStackBlock 
   // 堆Block _NSConcreteMallocBlock 
   
   // 2.2.1 捕获的变量 1
   
   {
       int a = 0;
       ^{
           NSLog(@"%ld",a);
       }
   }
   此时捕获变量:
   int a
       
   // 2.2.2 捕获的变量 2
   {
       __block int a = 0;
       ^{
           a = 2;
           NSLog(@"%ld",a);
       }
   }
   
   此时捕获的变量：
   __Block_byref_i_0 *i;	//保存的外部变量的引用，直接修改外部变量
   
   struct __Block_byref_i_0 {
       void *__isa;
       __Block_byref_i_0 *__forwarding;	// 这是因为block会被copy到堆上，为了保证到堆上后还能找到自己，所以使用一个始终只想自己的__forwarding找到自己。
       int __flags;
       int __size;
       int i;
   };
   
   // 2.2.2.a 举例
   struct _test test;	// 初始test
   test.number = "xxx";
   test.findStr = &test;	// 指向自己
   
   struct _test testcp;	// test的拷贝
   testcp.findStr = test.findStr;	//拷贝没有改变findStr的指向
   testcp.number = test.number;
   
   NSLog(@"%p %p %p %p",&test,test.findStr,&b,b.findStr);
   // 结果： 0x7ffee03924f0 0x7ffee03924f0 0x7ffee03924e0 0x7ffee03924f0
   // 所以无论 test 还是 testcopy 都能通过.number 找到 "xxx"
   ```

- `__unused` 用于标记可能用不到的变量
- Block 被copy的时机：1. 手动调用copy；2.返回值；3. 强引用；4. API中知名usingBlock

### 

## Meta Class

- Class 也是一个对象，Class的Classe就是元类，NSObject的子类的元类都是NSObject的元类，也就是他自己
- 对象的定义如下，所以满足这个条件的就是对象的一种

```objective-c
typedef struct objc_object { 
    Class isa; 
} *id; 
```

- Class的定义如下，所以Class也是对象

```objective-c
typedef struct objc_class *Class; 
struct objc_class { 
    Class isa; 
    Class super_class; 
    /* 以下依赖于 runtime 的具体实现 …… */ 
}; 
```

- isa 指定对象的类：对象的isa指向其类，而类的isa指向其元类（isa : xx is a xx?）

```objective-c
impl.isa = &_NSConcreteStackBlock; // Block的初始化比较只管的体现这一点，三种Block分别对isa有三种赋值
```

- Runtime 方法名

```objective-c
object_getClass() 		// object 开头 输入的是一个对象，返回其isa指向的类
class_getName() 		// class 开头 输入的时一个Class 那就直接返回class 的name字段
object_getClassName() 	// object 开头 输入的是一个对象 返回其isa指向的类的名称
    
// 举例
- (void)viewDidLoad {
    [super viewDidLoad];
    
    Chinese *chi = [Chinese new];
    Class chiClass = chi.class;
    NSInteger count = 5;
    while (count -- ) {
        printClass(chiClass);
        printClasss(chiClass);
        chiClass = object_getClass(chiClass);	//返回isa指向
    }
}

void printClass(Class cls){
    const char *className = object_getClassName(cls);	// 返回isa指向的类的名称
    printf("object_getClassName name %s %p\n",className,cls);
}

void printClasss(Class cls){
    const char *className = class_getName(cls);			// 返回该类的名称
    printf("class_getName name %s %p\n\n",className,cls);
}

/*
object_getClassName name Chinese 0x10006e910 // chiease 对象 的isa指向Chinese类
class_getName name Chinese 0x10006e910		 // chiease 对象 的类的名称

object_getClassName name NSObject 0x10006e8e8 // Chinese 类对象 的isa指向NSObject类
class_getName name Chinese 0x10006e8e8		  // Chinese 类 的名称

object_getClassName name NSObject 0x1b45e1ec8 // NSObject 类对象 的isa指向自己
class_getName name NSObject 0x1b45e1ec8		  // NSOjbect 类 的名称

object_getClassName name NSObject 0x1b45e1ec8 //..
class_getName name NSObject 0x1b45e1ec8

object_getClassName name NSObject 0x1b45e1ec8
class_getName name NSObject 0x1b45e1ec8
*/
```



## NSMethodSignature&NSInvocation

```objective-c

// 1. ctypes 生成

char *ctypes = "@if"; 
NSMethodSignature *sig = [NSMethodSignature signatureWithObjCTypes:"@if"];
    
NSLog(@"%ld %s %s %s",sig.numberOfArguments,[sig getArgumentTypeAtIndex:0],[sig getArgumentTypeAtIndex:1],sig.methodReturnType);

//2 i f @	// return 对象， 参数是 int， float


// 2. selector 生成
- (NSString *)test:(NSNumber *)c{
    return nil;
}

NSMethodSignature *sig = [ViewController instanceMethodSignatureForSelector:@selector(test:)];
NSLog(@"%ld %s",sig.numberOfArguments,sig.methodReturnType);
for (NSInteger i=0; i<sig.numberOfArguments; i++) {
    NSLog(@"%s",[sig getArgumentTypeAtIndex:i]);
}
// 结果
// 3 @	//3 个参数  reuturn 对象
// @	//self
// :	//test:
// @	//c

3. block signature
    
 NSInteger (^block)(NSString *st) = ^(NSString *st){
    return (NSInteger)0;
};

AspectBlockRef bb = (__bridge void *)block;
void *desc = bb->descriptor;
desc += 2 * sizeof(unsigned long int);
char *sig = (*(char **)desc);

NSMethodSignature *signa = [NSMethodSignature signatureWithObjCTypes:sig];
NSLog(@"%ld %s",signa.numberOfArguments,signa.methodReturnType);
for (NSInteger i=0; i<signa.numberOfArguments; i++) {
    NSLog(@"%s",[signa getArgumentTypeAtIndex:i]);
}

// 结果：
2 q	//2个参数 返回longlong
@?	//对象 未知(函数指针)
@"NSString"	//NSString 对象


    
```



## Runtime过程

![Apsects Runtime 流程](../../../images/aspects.png)