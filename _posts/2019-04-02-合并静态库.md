## 前言

静态库合并需要提取静态库的.o 文件，然后重新打包

直接使用lipo -create 合并静态库是不行的，因为同一个架构（arm64, armv7, armv7s, i386, x64_64等）的静态库不能合并



## 合并静态库

1. 分离静态库中的不同架构部分

   - lipo -info libxxx.a 查看当前库支持的架构，例如 arm64,armv7
   - 分离arm64： lipo libxxx.a -thin arm64 -output libxxx_arm64.a
   - 分离armv7 ：lipo libxxx.a -thin armv7 -output libxxx_armv7.a

2. 提取各架构的.o目标文件

   - cd 到想要存放.o 文件的目录（同一个架构的目标文件放在一个目录 方便后面打包）
   - 导出.o文件 ：ar -x /xxx/libxxx_arm64.a 

3. 将同一架构的所有.o文件打包成一个静态库

   - 假设所有.a 文件都已经导出到一个充满.o文件的文件夹, cd到该目录下
   - 生成新的对应的 .a 文件：libtool -static -o /xxx/libxxxnew_arm64.a *.o
   - 同样方法生成其他架构

4. 合并不同架构的静态库

   lipo -create libxxxnew_arm64.a libxxxnew_armv7.a -output libcombine.a



## 脚本

合并不同.a 文件中的arm64 和 armv7 架构

```bash
# !/bin/bash

echo "----合并开始----"

# 获取当前目录
currentDir="$(pwd)"

# 最终结果路径
finalLibDir="$currentDir/finallib"

# 原始库位置
originLibDir="$currentDir/origin"

# 重新创建库位置
recreateDir="${currentDir}/recreated"

# 库分离位置
explitDir="libexplit"
libExplit="$recreateDir/$explitDir"

# 库合并位置
resultDir="libresult"
libResult="$recreateDir/$resultDir"

# 最终.a名称
libName="libxxxOther"

# .o文件位置
odir64="$libExplit/arm64"
odirv7="$libExplit/armv7"

# 不同平台新的 .a文件路径
libArm64="$libResult/${libName}_arm64.a"
libArmv7="$libResult/${libName}_armv7.a"

# 需要合并的库名
libsToCombine=("lib1.a" "lib2.a" "lib3.a")

# 不需要合并的库名
libsToCopy=("xx.framework" "lib4.a" "lib5.a" "xxx.md")


echo "1. 检查和创建目录-->"
for dir in $finalLibDir $recreateDir $libExplit $odir64 $odirv7 $libResult
do
	echo $dir
	if [ ! -e $dir ]
	then
		mkdir $dir
	fi
done

echo "2. 从原始库目录中拷贝需要合并的库-->"
for lname in ${libsToCombine[@]}
do
	echo "$lname"
	cp -f "$originLibDir/$lname" "$recreateDir/"
done


echo "3. 分离出库中的目标.o文件-->"
for liba in $(ls $recreateDir)
do
	if [[ $liba != $explitDir && $liba != $resultDir ]]
	then
		# .a 文件名长度
		libNameLength=${#liba}

		# 去后缀名称
		name=${liba:0:libNameLength-2}

		#arm64 .a 名称
		arm64Name="${name}_arm64.a"

		# 分离出 arm64 .a 文件
		lipo "$recreateDir/$liba" -thin arm64 -output "$libExplit/$arm64Name"

		# armv7 .a 名称
		armv7Name="${name}_armv7.a"

		# 分离出 armv7 .a 文件
		lipo "$recreateDir/$liba" -thin armv7 -output "$libExplit/$armv7Name"

		# 获取 .o 文件
		cd $odir64
		ar -x "$libExplit/$arm64Name"
		echo "$arm64Name"

		cd $odirv7
		ar -x "$libExplit/$armv7Name"
		echo "$armv7Name"
	fi
done


echo "4. 将同一平台所有 .o 打成新的 .a 文件-->"
cd $odir64
libtool -static -o $libArm64 *.o
echo "${libName}_arm64.a"

cd $odirv7
libtool -static -o $libArmv7 *.o
echo "${libName}_armv7.a"

echo "5. 合并不同平台 .a 文件-->"
cd $libResult
lipo -create $libArm64 $libArmv7 -output "${libName}.a"
echo "${libName}.a"

echo "6. 拷贝最终结果-->"
echo "${libName}.a"
cp -f "$libResult/${libName}.a" "$finalLibDir/${libName}.a"

for lname in ${libsToCopy[@]}
do
	echo "$lname"
	cp -rf "$originLibDir/$lname" "$finalLibDir/$lname"
done


echo "7. 显示最终结果-->"
open $finalLibDir

echo "----合并结束----"
```



## 参考

1. [合并SDK](https://blog.csdn.net/qq_26968709/article/details/51164104)
2. [Shell教程](http://www.runoob.com/linux/linux-shell.html)

