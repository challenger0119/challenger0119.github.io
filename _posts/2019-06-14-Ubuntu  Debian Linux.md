# Ubuntu / Linux / Debian 使用笔记

**Ubuntu耳机声音设置**

插入耳机或者麦克风没反应：终端打开 alsamixer 找到 headphone / mic 选中后向上调节到最大

**2017/11/08 23:59 添加环境变量**

```shell
export PATH=$PATH:/xxx
```

**2017/05/16 14:23 中文乱码问题**

```shell
# 查看 locale
vim ~/.bashrc
# 添加：
export LC_ALL=en_US.UTF-8   export LANG=en_US.UTF-8
# 生效
source ~/.bashrc
```

**2017/05/10 13:00 获取时间戳**

`date +%s`

**2017/05/10 09:00 root账户登陆**

```shell
# 设置root密码 ： sudo passwd root
# 修改配置文件：/usr/share/lightdm/lightdm.conf.d/50-ubuntu.conf

[SeatDefaults] # 16.04 这里是[Seat:*]
user-session=ubuntu
greeter-show-manual-login=true #手工输入登陆系统的用户名和密码
allow-guest=false #不允许guest登录
```

**2017/05/02 20:38 C debug command**

```shell
gdb a.out
list show source code
break set breakpoint
run  run the app
print 
continue
step
return
```

<http://blog.csdn.net/feixiaoxing/article/details/7199643>

**Ubuntu 中文版双系统安装第一件事**

1，安装上第一件事请 更新语言 否则输入法崩溃

2，锐捷登录 参数 d1 dhcp

3，grub /etc/default/grub windows在4  然后执行 update-grub命令



**2017/03/18 15:36 安装jre并添加path**

```shell
# 安装jre  
apt install default-jre

# 添加PATH
/etc/profile

export JAVA_HOME=/home/miaoqi/jre1.8.0_121
export JRE_HOME=/home/miaoqi/jre1.8.0_121
export CLASSPATH=.:$CLASSPATH:$JAVA_HOME/lib:$JRE_HOME/lib
export PATH=$PATH:$JAVA_HOME/bin$:$JRE_HOME/bin

source /etc/profile

# 这样添加应该是所有用户生效，但是其他用户不一定能够访问这个路径，所以不如修改 ./home/miaoqi/.bashrc

```

**查看进程**

`ps -e|grep 查运行的程序 -ax 等`

**生成启动打开文件**

```shell
[Desktop Entry]
Type=Application
Name=Ruijie
Comment=Ruijie Launcher
Exec=/home/miaoqi/rj.sh
Categories=Development;
Icon=/home/miaoqi/图片/Computer_icon.png
StartupNotify=true
Terminal=true
```

*特别注意，不能有多余空格*

**更新 Adobe Flash**

```shell
# 目录
.mozilla/plugins (需要新建)
cd installxxxxx
cp -r usr/ /
cp libflashplayer /home/miaoqi/.mozilla/plugins
```

**定时器 crontab**

`crontab -e`

| 精度 | 举例                    |
| ---- | ----------------------- |
| 分钟 | 0 - 59                  |
| 小时 | 0 - 23                  |
| 天   | 1 - 31                  |
| 月   | 1 - 12                  |
| 星期 | 0 - 6       0表示星期天 |

举例：

\* * * * *                  # 每隔一分钟执行一次任务  

0 * * * *                  # 每小时的0点执行一次任务，比如6:00，10:00  

6,10 * 2 * *            # 每个月2号，每小时的6分和10分执行一次任务  

*/3,*/5 * * * *          # 每隔3分钟或5分钟执行一次任务，比如10:03，10:05，10:06  

<http://www.cnblogs.com/daxian2012/articles/2589894.html>

**Debian sudo**

```shell
su -  #切换到root下
apt-get install sudo  #安装
# 把需要应用sudo的用户添加到里面
visudo 
＃ 这个命令会编辑/etc/sudoers文件，在root下面下面添加如下信息debian是用户名
# User privilege specification
root ALL=(ALL:ALL) ALL
debian  ALL=(ALL:ALL) ALL
```

**下载工具**

```shell
# 主下载器
sudo apt install uget
# 多线程现在插件
sudo apt install aria2
```

