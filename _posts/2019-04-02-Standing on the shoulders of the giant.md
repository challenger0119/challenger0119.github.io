## SDWebImage

- 架构清晰：分离模块功能，每一个模块都能单独工作
- NSMapTable：创建weak内存管理的“字典”，同时其可以装指针型 ——`SDImageCache`
- 属性访问线程安全问题
- 图片数据 Data的第一个字节 存储了图片的格式信息 ——`NSData+ImageContentType `
- 图片的数据流加载：收到多少数据加载多少——`SDWebImageImageIOCoder`
- Gif 图片数据 可以通过CG接口拿到其图片数量、动画时间等信息——`SDWebImageGIFCoder`
- CoreGraphics 的使用
- 使用nonnull nullable等新特性 加强接口安全
- EXIF orientation 信息和iOS orientation信息存在转换关系——`SDWebImageCoderHelper`

## AFNetworking

- NS_DESIGNATED_INITIALIZER 用来保证所有的属性都能被初始化
- FOUDATION_EXPORT 创建字符串常量 
- 使用bundleID 设计字符串常量
- 使用Block封装上次代理
- 使用信号量 将异步回掉写成return
- 利用 _cmd 代替方法的名字
- NSURLSessionDataTask 并行初始化的时候可能存在相同的taskIdentifier（本应该是唯一的）， 所以AFN将其初始化放在自己的串行线程中进行。 
- SDK中暴漏的属性考虑readonly
- 对象无关的操作可以使用静态方法
- Array 使用 KVO 手动通知
- 使用串行和并行结合实现读写的同步问题（内存管理）

## AsyncDisplayKit