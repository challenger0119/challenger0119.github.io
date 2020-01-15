## SVN

- 查看状态：`svn status`
- 更新: `svn update`
- 删除: `svn delete`
- 更换URL: `svn sw --relocate https://xxx/efamily https://xxx/efamily `(前面是旧的 后面是新的)

#### 分支

新建分支：

`svn copy http://xx http://xx -m "xxx"`

合并分支：

`svn merge http://from`

#### 搜索

搜索 关键字 xxx 后3行显示

`svn log | grep -A 3 "xxx" `

搜索 关键字 xxx 前3行显示

`svn log | grep -B 3 "xxx" `

搜索 20 条 包含 xxx的

`svn log -l20 --search xxx`

#### 移除版本控制

- 将已经在版本控制的文件移除，同时保留本地文件`svn rm --keep-local xxx`

- `svn propset svn:ignore -F .cvsignore`    导入CVS版本控制的忽略模式

- 推荐使用 `propedit` 类似 `git`的`.gitignore`文件，方便：

  `svn propedit svn:ignore Directory` 在 Directory 目录创建忽略模式，这时候会打开一个文件，在文件中输入模式就行 例如 `*.nib` 忽略nib文件。

  如果提示`SVN_EDITOR`  错误，说明没有默认的编辑器:

  ```shell
  # 打开用户配置
  vim ~/.bash_profile
  
  # 添加
  export SVN_EDITOR=vim
  
  # 注册
  source ~/.bash_profile
  ```

[Ref](http://svnbook.red-bean.com/en/1.7/svn-book.html)

***SVN 命令支持简写***



#### SVN Server

- 版本检查： `svnserve -version`
- 创建目录：`svnadmin create repository` ——在当前目录生成了一个 repository 版本库
- 修改配置文件 repository 下的 conf 目录下的配置 [参考](http://www.cnblogs.com/czq1989/p/4913692.html)

## Git

- 查看分支： git branch -a
- 拉分支： git checkout *branch*
- 撤销修改: git checkout -- xxx
- 查看日志：git log -n
- 忘记加入 .gitignore 的文件：git rm —cache xxx 
- 格式日志：“git log --pretty=format:"%h - %an, %ar : %s”

#### 添加远程Git 相关命令

1. 工程里git init
2. git remote add main https://github.com/challengerxxx  （主要）
3. git pull main master
4. git push --set-upstream main master （主要）
5. git push

#### 移除版本控制

`git rm -r --cached Libs/`



**主要命令以外的 配合使用**

### Git server

1. 添加用户 adduser git
2. 设置密码 vim /etc/passwd  --> 添加 passwd git 12345
3. 可以关闭ssh 将 `git:x:1001:1001:,,,:/home/git:/bin/bash` 修改为 `git:x:1001:1001:,,,:/home/git:/usr/bin/git-shell`
4. 在合适的地方创建git即可 例如 /home/git 下 可能没有这个目录 需要创建，git用户的默认目录
5. git init --bare xxx.git



## Command Line

- 命令行快速回到头部 CTRL + A 尾部 CTRL + E

## VIM

- 查找：

  - `/xx\c`  \c 标识大小写不敏感   n 继续查找下一个 
  - ?xx 查找上一个   # 继续查找上一个

- 替换：`:{作用范围}s/{目标}/{替换}/{替换标志}`  

  例如`:%s/foo/bar/g`会在全局范围(`%`)查找`foo`并替换为`bar`，所有出现都会被替换（`g`）。

  `:s/foo/bar/g` 当前行

  `:5,12s/foo/bar/g` 5-12行

  `:.,+2s/foo/bar/g` 向后两行

## VIMDiff

- 跳到下一个diff：`]c`
- 跳到上一个diff：`[c`  前面加数字可以调多个
- 把当前修改放到另一个文件 `dp` (put)
- 把别的修改拿过来` do` (obtain)
- 切换文件 `CTRL+W` 加方向
- 刷新对比 `:diffupdate`