## 预警发送
本扩展自带了邮件、短信、钉钉机器人、微信企业机器人、webhook等预警发送方式

## 安装


### 安装依赖
```bash
composer require hanxiaoqingcheng/warning
```

### 自动生成文件
```bash
php artisan vendor:publish
```
选择`Sy\Warning\WarningServiceProvider`
若没有生成配置文件，可以尝试先执行
```bash
php artisan cache:clear
```

### 执行数据库迁移
本扩展已经设计好预警日志表和预警用户表
```bash
php artisan migrate
```
需要配置数据库表：warning_user_account 和 warning_tpls

warning_user_account表配置示例：

|uid|uname|type|account|show|
| :-----| :-----| :-----| :-----|:-----|
|6|laowang|phone|13888888888|1|
|6|用户名可不填（uid=6相当于用户组，不填写表示默认值）|phone|13999999999|1|
|7|  |email|123@qq.com|1|

warning_tpls表配置示例：

|uid|uname|product|warning_name|type|warning_tpl|show|
| :-----| :-----| :-----| :-----| :-----| :-----|:-----|
|6|用户名，可不填，如6是某公司注册的账号，下面N个研发或者研发负责任，只需要配置不同uname|产品名称，必填|预警名称，可不填|预警类型：枚举,目前支持5种(email/phone/weixin/dingding/webhook)|1|


### 短信发送
本扩展使用的是聚合数据的短信发送功能，申请账号查看官网[聚合数据短信发送](https://www.juhe.cn/docs/api/id/54)


### 开启用户发送短信、邮件、钉钉机器人、webhook、微信企业机器人
开启任何一个功能，只需在数据库`warning_user_account`表配置用户即可使用

### 配置Mail
本扩展依赖laravel框架，并没有引入laravel已经存在的扩展。
配置laravel框架下config目录下的mail.php，不了解的可以自行查看laravel官方文档

### 调用

创建的数据warning_tpls表，字段product => $product,uid => $uid,uname => $uname,warning_name=> $warningName,字段名对应的参数为下面调用示例的入参

$tplValue字段规则：warning_tpls表中的warning_tpl字段，所有参数以#xxx#的形式，参数前后用#号。

数据库warning_tpl字段模板如果是email,webhook,weixin,dingding,示例：`您扫描的关键字「#keywords#」，有#num#个新增未知风险待确认，请您前往 #url# 查看。` 

如果是phone，按照聚合网站的要求，需要输入的参数tpl_value字段就可，示例：#code#=1234&#uname#=小明

$tplValue示例：`#keywords#=聚合数据&#num#=10&#url#=https://scan.juhe.cn`

```php
//$username可不传，表示给全部$uid成员发预警，若传$username，表示给$uid下面的$username传预警
//$warningName可不传。传值表示根据该预警名称配置的预警模板发送预警
event(new Sy\Warning\Events\MsgPublishEvent($product, $tplValue, $uid, $username, $warningName));
```

