# 类 Unix 操作系统使用指南

一般使用的命令行都是 Linux 和 MacOS 都能用的，这是因为他们的核心都是类Unix的操作系统。

## 类 Unix 系统系统简介

### Linux

Linux 是一套免费使用和自由传播的类 Unix 操作系统, 有很多版本，常用的有 Ubuntu 和 CentOS 等，如下图:

![](../../../images/unixlike.jpg)

### MacOS

MacOS 核心是 Darwin， Darwin 也是  iOS 操作系统核心，Darwin 也是一种类 Unix 的操作系统

## 命令

### 基本操作

- `clear` 清楚窗口
- `cd` 跳转目录
- `mkdir` 创建目录

- `ls` 显示目录内容
- `touch` 创建文件
- `mv` 移动文件(重命名)
- `cp` 复制文件(同时可重命名)
- `rm` 删除文件

Demo：

```shell
# 创建一个 guide 目录
mkdir guide

# 进入 guide
cd guide

# ls 查看里面内容
ls

# 创建一个 guide.md 文件
touch guide.md

# ls 查看里面内容
ls

# ls -l 查看文件显示详细信息
ls -l

# ls -a 查看所有文件包括隐藏文件 
# 会有三个文件 . / .. / guide.md 其中 . 表示当前目录 .. 表示上层目录(cd .. 返回上层) 
ls -a

# ls -al 组合上面两个命令

# 创建子目录 subguide
mkdir subguide

# ls 查看
ls

# 复制 guide.md 到子目录 并重命名为 guide.txt
cp guide.md subguide/guide.txt

# ls 查看subguide目录 不用 cd 进去 还在当前目录
ls subguide

# 把guide.md 移动到 subguide 不重命名
mv guide.md subguide

# ls 查看 subguide

# cd 到 subguide
cd subguide

# ls 查看
ls

# 删除 guide.txt
rm guide.txt

# 退到上层目录
cd ..

# ls 查看
ls

# 删除 subguide 目录 包括里面的文件 -rf r表示递归删除 f表示强制删除 组合参数
rm -rf subguide 

# ls 查看

# 返回用户目录 
cd

```

### Vim

Vim 是 广泛使用的 基于命令行的编辑器 是 Vi 的升级版 可能需要安装

- `vim guide.md` 开始编辑 guide.md 文件

- `i` 移动光标到想输入的地方 点击键盘 `i` 进入插入模式 左下角会有 —Insert-- 显示, 如图

  ![](../../../images/viminsert.png)

- `ESC` 编辑完成后 点击键盘 `ESC` 退出编辑 Insert消失

- `:` 输入分号 开始输入编辑提交命令

  - `w` 写入
  - `q` 退出 一般编辑完成后使用 `wq` 组合命令 "写入并退出"
  - `!` 强制执行 如果中途放弃编辑 `q!` 强制退出

Demo:

```shell
# 开始编辑 guide.md
# vim 后面跟上一个名字 如果没有它就会创建这个文件 所以一般创建文件不用touch 直接 vim xxx 创建开始编辑
vim guide.md 

# i 进入插入模式
# 输入点啥
# ESC 退出
# 分号 进入命令模式
# 输入wq 回车 写入并推出
# cat 命令查看文件内容 
cat guide.md
# 或者 vim查看
vim guide.md
```

### Git

### MacOS 特例

## 脚本

# 参考

[Linux 简介](https://www.runoob.com/linux/linux-intro.html)

[MacOS Wiki](https://zh.wikipedia.org/wiki/MacOS)

[Darwin Wiki]([https://zh.wikipedia.org/wiki/Darwin_(%E6%93%8D%E4%BD%9C%E7%B3%BB%E7%BB%9F)](https://zh.wikipedia.org/wiki/Darwin_(操作系统))