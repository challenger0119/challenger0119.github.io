# Appium KIF UI自动化测试



## accessibility

#### accessibilityLabel——VoiceOver 辅助标识

- 系统元素一般都会设定
- 需要具有意义： VoiceOver 会将其读给用户听

```objective-c
- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath{
    UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:reusableIdentifier forIndexPath:indexPath];
    cell.textLabel.text = self.dataSouce[indexPath.row].name;
    cell.backgroundColor = self.dataSouce[indexPath.row].isDirectory ? [UIColor cyanColor] : [UIColor whiteColor];
    
    cell.accessibilityLabel = [NSString stringWithFormat:@"第%ld项 %@", indexPath.row, self.dataSouce[indexPath.row].name];	// 点击提示读出当前行号和文件名
    cell.accessibilityHint = self.dataSouce[indexPath.row].isDirectory ? @"这是一个文件夹 可打开" : @"这是一个文件";	// 提示该内容是什么类型的文件 可以做什么操作
    return cell;
}
```

#### accessiblityIdentifier——UI元素标识

- UIAccessibilityIdentification  协议属性
- UIKit 实现了该协议的类：UIView UIBarItem UIImage 



## Appium

#### 原理：

- 目前是基于XCUITest，Client/Server 模式
- 在设备上安装一个APP，也是服务端 WebDriverAgent（Facebook的一个项目），接收Appium Client发来的测试命令。WebDriverAgent 通过XCUITest的API 来完成测试。
- 通过元素的 accessibilityLabel、accessiblityIdentifier、className、xpath等查找元素



#### 使用体验（iOS开发者）：

##### 优点：

- 测试用例不需要写在APP内部，可直接测试已有APP
- 可以使用Python Java Javascript 等多种语言编写用例

##### 缺点：

- 只能运行在iOS 9.3 以上的设备
- C/S模式 查找元素耗时，尤其是table cells 较多时候查找一组元素很慢（原因在于按照cellClass查找会尝试拿到所有的cells）
- 不能使用OC 和 swift 编写用例
- Client通过Server返回的 UI的 xml布局 解析UI元素，不能像原生一样直接使用控件的类操作



#### 结论

适合非iOS开发的专业测试人员



## KIF

#### 原理：

- 基于XCTest 和 UI操作的私有API：利用XCTest的优点来进行UI测试
- 推荐使用accessibilityLabel标识来查找元素，也可以使用accessiblityIdentifier、className等



#### 使用体验（iOS开发者）：

##### 优点：

- OC swift 编码
- 原生界面元素类支持
- 元素查找更友好，也更快 （table 会自动滚动到要点击的位置，table元素每次只拿当前显示个数）

##### 缺点：

- 测试用例需要写在项目中



#### 结论：

适合iOS开发人员



#### Jenkins集成：

##### 安装测试结果统计支持

```shell
# Jenkins 统计结果使用Junit 插件 这个库用来转换格式
gem install ocunit2junit

# 统计代码覆盖率
gem install slather
```

```shell
# 测试
xcodebuild test -workspace xxx.xcworkspace -scheme xxxTests -destination 'platform=iOS,name=xxx' -configuration Debug -enableCodeCoverage YES 2>&1 | ocunit2junit

# 统计代码覆盖率 和 测试结果
slather coverage --html --input-format profdata --binary-basename xxx.app --scheme xxxTests --workspace xxx.xcworkspace --configuration Debug --ignore **View** --ignore **AppText** --output-directory reports
```

详细看参考链接 3



### 参考

1. [Appium The XCUITest Driver for iOS](http://appium.io/docs/en/drivers/ios-xcuitest/)
2. [为什么使用KIF——美团方案](https://tech.meituan.com/iOS_UITest_KIF.html)
3. [jenkins 自动化集成](https://www.jianshu.com/p/dea69545bf4e)
4. [测试方案调研](https://juejin.im/post/5b011de951882542672654d)