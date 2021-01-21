<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<!-- start -->
<table style="width: 640px; margin: 50px auto; background: #fff; font-family: 'Microsoft Yahei'; border-collapse: collapse; border: 1px solid #ccc;" cellpadding="0" cellspacing="0">
    <tbody>
    <tr>
        <td height="100px" style="border-bottom: 1px solid #ccc; padding-left: 32px;">
            <a href="https://www.scan.juhe.cn"><img width="180" height="42" src="https://juhecdn.oss-cn-hangzhou.aliyuncs.com/images/email/cloudLogo.png" alt="聚合云监测"></a>
        </td>
    </tr>
    <tr>
        <td>
            <div style="font-size: 24px; font-weight: bold; color: #333; line-height: 36px; padding: 30px 40px 10px 40px;">Hi~ {{$warning->uname}}</div>
            <div style="font-size: 24px; line-height: 36px; color: #333; padding: 0 40px 30px 40px;">您扫描的关键字<b>「{{$warning->keyword}}」</b>,有<b style="color: #F42020;">{{$warning->count}}个</b>新增未知风险待确认，请前往<a style="color: #207CF6; text-decoration: underline;" href="https://scan.juhe.cn/">https://scan.juhe.cn/</a>查看</div>
        </td>
    </tr>
    <tr>
        <td style="padding: 10px 40px 40px 40px;">
            <img width="560" height="408" src="https://juhecdn.oss-cn-hangzhou.aliyuncs.com/images/email/cloudMain.png" alt="">
        </td>
    </tr>
    </tbody>
</table>
<!-- end -->
</body>
</html>
