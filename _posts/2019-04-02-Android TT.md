# Android TT

Ubuntu 18.04

Android Studio 3.2

华为 Android 5.1



1. **AS gradle 下载不了**

   - 使用 Build 中显示的地址用uget多线程手动下载，快很多
   - 设置AS使用自定义的 gradle

2. **Make Project 后 无法运行，Edit Configuration 显示 no module 没有 app**

   原因：因为最开始环境没搞好，导致依赖库没有安装

   解决：不小心选择了project structure -> module 下添加了 app (自己的app, 这应该是不需要的)，编译报错后，删除之，出发了重新下载依赖。现在看来 clean 重新编译可能就行

3. **一切搞定，连接手机，run 提示 adb push  device not found**

   原因：此时手机确实连接了，也开启了USB调试，但是从AS 的设备管理上看设备是Unkown 没有序列号

   解决：

   - 看不到序列号问题：在拨号界面输入：`*#*#2846579#*#*` 进入测试菜单界面 ，然后USB端口使用Google模式

   - 序列号显示后，提示无权限访问 no promission: 驱动问题

     ```shell
     # 添加设备usb支持
     sudo vim /etc/udev/rules.d/70-android.rules
     SUBSYSTEM=="usb",ATTRS{idVendor}=="12d1",ATTRS{idProduct}=="1073",MODE="0666"
     
     # idVendor 和 idProduct 查看
     lsusb  # 显示当前usb设备
     Bus 004 Device 001: ID 1d6b:0003 Linux Foundation 3.0 root hub
     
     # 其中 1d6b 为 vendor  0003 为product
     ```
