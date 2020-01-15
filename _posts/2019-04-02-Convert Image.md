## 图片转为8 Bits

### 查看图片信息

`sips -g all xxx.png`

### 转为sRGB profile 8Bits

`sips -m "/System/Library/Colorsync/Profiles/sRGB Profile.icc" xxx.png --out xxx.png`



[Xcode8 bug](https://dkjone.github.io/2017/10/13/xcode常见错误处理.html)\



### Xcode 编写程序执行脚本

[Swfit命令行程序 执行 sips shell命令](https://github.com/challenger0119/tools/blob/master/localization/Localization/Libs/ShellWork.swift)



