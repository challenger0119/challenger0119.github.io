### iOS Photos & iCloud

#### 1. 获取照片 fetch

##### fetch 所有照片

```objective-c
PHFetchResult<PHAsset *> *resultAll = [PHAsset fetchAssetWithOptions:options];
```

##### fetch 相册

```objective-c
PHFetchResult<PHAssetCollection> *resultCollection = [PHAssetCollection fetchAssetCollectionsWithType:type subtype:subtype options:options]
```

##### fetch 特定相册中照片

```objective-c
PHFetchResult<PHAsset *> collectionResult = [PHAsset fetchAssetsInCollection:collection options:options]
```

##### PHFetchOptions

通过 predicate 和 sortDescritptors 谓词来过滤或者排序 asset，可用于的字段一般来说就是PHFetchResult包含的对象的属性。

例如获取所有照片时候PHFetchResult里面是 PHAsset 对象，可用的属性就是：`SELF, localIdentifier, creationDate, modificationDate, mediaType, mediaSubtypes, duration, pixelWidth, pixelHeight, isFavorite (or isFavorite), isHidden (or isHidden), burstIdentifier`

相对来说 PHAssetCollection 就是: `SELF, localIdentifier, localizedTitle (or title), startDate, endDate, estimatedAssetCount`

***还不是所有的属性***

##### PHAssetCollectionType

```objective-c
typedef NS_ENUM(NSInteger, PHAssetCollectionType) {
    PHAssetCollectionTypeAlbum      = 1,
    PHAssetCollectionTypeSmartAlbum = 2,
    PHAssetCollectionTypeMoment     = 3,
}
```

- Album 平常的相册 你不创建就没有, 显示在 “我的相簿” 下面那堆 微博  QQ 等
- SmartAlbum 自动生成的相册 自拍 截屏 等等
- Moment 根据地理位置 时间等信息生成的照片集

##### PHAssetCollectionSubtype

```objective-c
typedef NS_ENUM(NSInteger, PHAssetCollectionSubtype) {
    
    // PHAssetCollectionTypeAlbum regular subtypes
    PHAssetCollectionSubtypeAlbumRegular         = 2,
    PHAssetCollectionSubtypeAlbumSyncedEvent     = 3,
    PHAssetCollectionSubtypeAlbumSyncedFaces     = 4,
    PHAssetCollectionSubtypeAlbumSyncedAlbum     = 5,
    PHAssetCollectionSubtypeAlbumImported        = 6,
    
    // PHAssetCollectionTypeAlbum shared subtypes
    PHAssetCollectionSubtypeAlbumMyPhotoStream   = 100,
    PHAssetCollectionSubtypeAlbumCloudShared     = 101,
    
    // PHAssetCollectionTypeSmartAlbum subtypes
    PHAssetCollectionSubtypeSmartAlbumGeneric    = 200,
    PHAssetCollectionSubtypeSmartAlbumPanoramas  = 201,
    PHAssetCollectionSubtypeSmartAlbumVideos     = 202,
    PHAssetCollectionSubtypeSmartAlbumFavorites  = 203,
    PHAssetCollectionSubtypeSmartAlbumTimelapses = 204,
    PHAssetCollectionSubtypeSmartAlbumAllHidden  = 205,
    PHAssetCollectionSubtypeSmartAlbumRecentlyAdded = 206,
    PHAssetCollectionSubtypeSmartAlbumBursts     = 207,
    PHAssetCollectionSubtypeSmartAlbumSlomoVideos = 208,
    PHAssetCollectionSubtypeSmartAlbumUserLibrary = 209,
    PHAssetCollectionSubtypeSmartAlbumSelfPortraits PHOTOS_AVAILABLE_IOS_TVOS(9_0, 10_0) = 210,
    PHAssetCollectionSubtypeSmartAlbumScreenshots PHOTOS_AVAILABLE_IOS_TVOS(9_0, 10_0) = 211,
    PHAssetCollectionSubtypeSmartAlbumDepthEffect PHOTOS_AVAILABLE_IOS_TVOS(10_2, 10_1) = 212,
    PHAssetCollectionSubtypeSmartAlbumLivePhotos PHOTOS_AVAILABLE_IOS_TVOS(10_3, 10_2) = 213,
    PHAssetCollectionSubtypeSmartAlbumAnimated PHOTOS_AVAILABLE_IOS_TVOS(11_0, 11_0) = 214,
    PHAssetCollectionSubtypeSmartAlbumLongExposures PHOTOS_AVAILABLE_IOS_TVOS(11_0, 11_0) = 215,
    // Used for fetching, if you don't care about the exact subtype
    PHAssetCollectionSubtypeAny = NSIntegerMax
}
```

- 如注释标注 subtypes 主要是两部分 ：Album 和 SmartAlbum
- Regular  就是通常创建的相册 
- synced 是iPhoto同步过来的相关内容
- imported 是从外部设备或者相机导入的
- MyPhotoStream CloudShared  iCloud的照片流 像照片应用中有专门的 tab
- Generic   `A smart album of no more specific subtype.`  并不是所有的smartAlbum 测试时候它是空的
- fetch策略是 没有找到对应subtype就返回所有，所以使用非smartAlbum的subtype (PHAssetCollectionSubtypeAlbumImported) 会返回所有smartAlbum；而使用generic 会返回空 因为它是空的；

##### PHCollectionList

返回特定的Collection集合：某一年的 moment 或者 所有用户创建的相册

#### 2. iCloud文件过滤

- 获取照片时候不允许网络，如果为空就说明本地没有

```objective-c
PHImageRequestOptions *option = [PHImageRequestOptions new];
option.networkAccessAllowed = NO;
[PHImageManager defaultManager] requestImageDataForAsset:(PHAsset *)asset options:(nullable PHImageRequestOptions *)options resultHandler:(void(^)(NSData *__nullable imageData, NSString *__nullable dataUTI, UIImageOrientation orientation, NSDictionary *__nullable info))resultHandler{
  if(imageData){
    //local
  }else{
    //iCloud
  }
}
```

其缺点是:

1. iCloud上下载到本地的低质量照片有时候也会认为存在，导致使用的不是原图
2. 比较耗时

- iOS9 以上有一个提供一个类 PHAssetResource 里面有一个未公开的属性 locallyAvailable 可以使用KVC的方法获取其值作为判断

```objective-c
NSArray *resourceArray = [PHAssetResource assetResourcesForAsset:asset];
            NSNumber *locallyAvailableNumber = [resourceArray.firstObject valueForKey:@"locallyAvailable"];

if (locallyAvailableNumber && ![locallyAvailableNumber boolValue]) {
  //iCloud
  continue;
}	
```

