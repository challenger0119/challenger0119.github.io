## 推荐的使用方法

1. 图片缩放

```objective-c
// 常规做法

+ (UIImage*)OriginImage:(UIImage *)image scaleToSize:(CGSize)size
{
    // 创建一个bitmap的context
    // 并把它设置成为当前正在使用的context
    UIGraphicsBeginImageContext(size);
    
    // 绘制改变大小的图片
    [image drawInRect:CGRectMake(0, 0, size.width, size.height)];
    
    // 从当前context中创建一个改变大小后的图片
    UIImage* scaledImage = UIGraphicsGetImageFromCurrentImageContext();
    
    // 使当前的context出堆栈
    UIGraphicsEndImageContext();
    
    // 返回新的改变大小后的图片
    return scaledImage;
}
```

上面这种方法绘制的图片占用的内存并不是其尺寸计算出来的内存大小（width x height x P)

```swif
//WWDC2018 推荐做法

func downsample(imageAt imageURL: URL, to pointSize: CGSize, scale: CGFloat) -> UIImage{
    let sourceOpt = [kCGImageSourceShouldCache : false] as CFDictionary
    // 其他场景可以用createwithdata (data并未decode,所占内存没那么大),
    let source = CGImageSourceCreateWithURL(imageURL as CFURL, sourceOpt)!
    
    let maxDimension = max(pointSize.width, pointSize.height) * scale
    let downsampleOpt = [kCGImageSourceCreateThumbnailFromImageAlways : true,
                         kCGImageSourceShouldCacheImmediately : true ,
                         kCGImageSourceCreateThumbnailWithTransform : true,
                         kCGImageSourceThumbnailMaxPixelSize : maxDimension] as CFDictionary
    let downsampleImage = CGImageSourceCreateThumbnailAtIndex(source, 0, downsampleOpt)!
    return UIImage(cgImage: downsampleImage)
}
```

