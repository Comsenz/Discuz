<p align="center"><img src="https://www.discuz.net/static/image/common/logo.svg"></p>


## 关于 Discuz! Q

**[Discuz Q 官方](https://discuz.chat)**

## 安装方法

服务器环境需求为： **PHP 7.2.5+** 和 **MySQL 5.7+**, 并且需要安装 [Composer](https://getcomposer.org/)。

## 内测下载 Discuz! Q

首先注册[腾讯云帐号](https://cloud.tencent.com)并[实名认证](https://console.cloud.tencent.com/developer/auth)，然后在[API密钥管理](https://console.cloud.tencent.com/cam/capi)处新建一个密钥，运行列命令可下载 Discuz Q

```
composer config -g http-basic.cloud.discuz.chat ${QCLOUD_SECRET_ID} ${QCLOUD_SECRET_KEY}

composer create-project --prefer-dist qcloud/discuz --repository=https://cloud.discuz.chat
```

## 正式发布后下载 Discuz! Q

```
composer create-project --prefer-dist qcloud/discuz

cd resources/frame
npm install
npm run build
```

## 感谢

### 背景故事

`Discuz! Q`项目由于是从 0 到 1，介于我们的目标，如果从第一行代码开始编写，是极为庞大的工程。想想`Discuz!X`，代码量依赖 10 多年的时间的积累，才完善出各种工具类、自己的框架及插件机制等。

在此背景下，我们必须借助开源的力量，才得以快速构建出`Discuz! Q`。以下是整个`Discuz! Q`中所用到的技术栈，在此特别感谢他们：

<p><a href="https://laravel.com/"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="200"></a></p>
<p><a href="https://symfony.com/"><img src="https://symfony.com/images/logos/header-logo.svg" width="200"></a></p>
<p><a href="https://getlaminas.org/"><img src="https://getlaminas.org/images/logo/laminas-foundation-rgb.svg" width="200"></a></p>

[FastRoute](https://github.com/nikic/FastRoute)

[Guzzle](http://guzzlephp.org/)

[thephpleague](https://thephpleague.com/) 

[s9etextformatter](https://s9etextformatter.readthedocs.io/)

[overtrue](https://overtrue.me/)

[intervention](http://image.intervention.io/)

[monolog](https://github.com/Seldaek/monolog)

[whoops](https://github.com/filp/whoops)

[vue](https://vuejs.org/)

[Vant](https://youzan.github.io/vant/#/zh-CN/)

[element-ui](https://element.eleme.cn/#/zh-CN)
