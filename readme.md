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

### 短信发送
本扩展使用的是聚合数据的短信发送功能，warning.php文件下的SMSKey和SMSTpl需要到聚合官网[聚合数据短信发送](https://www.juhe.cn/docs/api/id/54)申请


### 开启用户发送短信、邮件、钉钉机器人、webhook、微信企业机器人
开启任何一个功能，只需在数据库`warning_user_account`表配置用户即可使用

### 配置Mail
本扩展依赖laravel框架，并没有引入laravel已经存在的扩展。
配置laravel框架下config目录下的mail.php，不了解的可以自行查看laravel官方文档




