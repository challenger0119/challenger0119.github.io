#### 自动引用计数
##### GNUstep alloc 的实现
- alloc 创建对象的时候内存表现：头部是一个obj_layout 的结构体，里面有retaind 值整型，后面则跟着对象的内存，初始化的时候置0；
    看起来是这样： |`objc_layout` `object`|。
- alloc 返回的指针指向 object，obj_layout是这样：

```
struct obj_layout{
    NSUInteger retained;
}
```

- 所有获取其retainCount的时候指针会减去头大小（obj_layout） 回到顶部读取retained值
- 内存管理都是操作对象obj_layout的retained

##### Apple alloc 的实现
- 不同与GNUstep使用对象头部的obj_layout 而是使用散列表统一管理所有对象的retain值
- 这样做的好处是：1. 对象内存块的分配不需要考虑内存块头部；2. 记录表中存有内存块的地址，通过记录可以追溯到内存块

##### ARC
- NSObject 对象实现了autorelease 方法，方法做的事情就将对象自己加到当前的autoreleasepool中
- autureleasepool 释放的时候遍历加入的对象然后逐个release
- 其实weak指针指向的对象 都自动加入了autoreleasepool以保证在pool结束对象存在；其加入的时机是调用了该对象的方法, 应当避免过多直接使用weak对象，可以将其转为strong来使用，从而避免每次调用方法的时候都将weak加入自动释放池。
- id的指针 和 对象的指针也会隐式增加__autorelease修饰符：例如NSError对象使用的时候
```
//id 指针
id *idobj;
// NSOjbect 对象指针
NSObject **obj;

NSError *error = nil;
NSError **err = &error; //会出错 因为 error 是强引用__strong 而**err 其实是 * __autorelease *err;
```
- id 与 void* 的转换
```
//三种转换 __bridge; __bridge_retained; __bridge_transfer
id obj = [NSObject new];

换和  void* 与id 的转换类似，其实质都是对象与c指针的转换
```
- `allowWeakReference` 和 `allowStrongReference` 可以控制对象是否接受弱引用和强引用 

#### Block
- Block里面如果处理C 语言的数组是不能吸怪，这时候可以使用指针代替数组
- obj_object 和 obj_class 的结构体是一样的 两个又分别对应id 和 Class
```
typedef struct obj_object {
    Class *isa;
} *id;

typedef struct obj_class {
    Class *isa;
} *Class;
```
- block是oc对象 : oc 对象的实质，举例来说
```
@interface Myobject : NSObject
{
    int val0;
    int val1;
}
@end;

//基于obj_object 结构体，该对象的结构体为：
struct Myobject {
    Class *isa;
    int val0;
    int val1;
}

//ps 这实质是 NSOjbect 类的一个扩充；

```
所以，oc由类生成对象 的实质就是 生成该类的对象的结构体实例 (当然，该类的对象是该类生成的) 然后对象通过成员变量的isa指针 指向该类的实例

*关于类 和 对象 这一段比较绕口*

而对于类的实例 class_t 如下 （struct Myobject 里面的 isa 指向 class_t）:
```
struct class_t {
    struct class_t *isa;
    struct class_t *superclass;
    Cache cache;
    IMP *vtable;
    uintptr_t data_NEVER_USE;
}
```
而block的结构体：
```
struct _main_block_impl_0 {
    void *isa;
    int Flag;
    int Reserved;
    vod *FuncPtr;
    struct __main_block_desc_0 *Desc;
}

//这就相当于 基于 obj_object 结构体的 oc 类的对象的结构体
```
而初始化 __main_block_impl_0 时候 `isa = &_NSConcreteStackBlock` 中的 `_NSConcreteStackBlock` 就相当于 `class_t`;

- Block 的初始化
```
int main(){
    void (^blk)(void) = ^{
        print("xxx");
    };
    
    blk();
    return 0;
}

//clang 编译上面代码做了几件事情:
//1. 拿到 Block 的定义

struct __block_impl {
    void *isa;
    int Flag;
    int Reserved;
    void *FuncPtr;
}

//2. 函数定义 与 初始化 
//这部分的 main 指的是 从main函数段调用； 0 表示 函数段第一次使用（clang 匿名策略）

static struct __main_block_desc_0 {
    unsigned long reserved;
    unsigned long Block_size;
}__main_block_desc_0_DATA = {
    0,
    sizeof(struct __main_block_impl_0)
}

struct __main_block_impl_0 {
    struct __block_impl impl;
    struct __main_block_desc_0 *Desc;
    
    __main_block_impl_0(void *fp, struc __main_block_desc_0 *desc, int flag = 0){
        impl.isa = &_NSConcreteStackBlock;
        impl.Flag = 0;
        impl.Reverved = 0;
        impl.FuncPtr = fp;
        Desc = desc;
    }
}


static void __main_block_func_0(struct __main_block_impl_0 *__cself){
    print("xxx");
}

// 3. main 执行

int main(){
    //*blk->FuncPtr(blk);
    (void (*)(struct __block_impl *))((struct __block_impl *)blk->FuncPtr)((struct __block_impl *)blk);
}
```
- Block截获自动变量：其实就是将自动变量值保存在自己的结构体实例中 `__main_block_impl_0`

- \_\_Block 的变量 是一个\_\_block的结构体 它会被复制到堆上

  ```objective-c
  struct __Block_byref_val_0 {
    void *__isa;
    __Block_byref_val_0 *__forward;	//指向该结构体在堆上的地址，保证其在Block作用域外可以访问
    int __flags;
    int __size;
    int val;
  }
  ```

- 有usingBlock的方法传入block时不需要copy

- GCD使用block时候也不需要copy

- \_\_block 也可以用来避免循环引用 但是要保证 \_\_block的变量被释放（设为nil）

#### Grand Central Dispatch

- 可以使用Dispatch I/O 分块多线程读取文件
- 当需要使用kqueue的时候考虑使用更为简单的Dispatch Source（占CPU少，不占资源，内核范围）