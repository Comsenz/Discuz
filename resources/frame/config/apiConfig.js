/**
 * 接口配置文件
 */

module.exports = {
  'register': '/register',              //用户名注册
  'login': '/login',                     //用户名登录
  'users': '/users',                     //用户信息
  'sendSms': '/sms/send',                //发送验证码
  'smsVerify': '/sms/verify',            //短信验证
  'wechat': '/oauth/wechat/user',        //微信接口
  'authority': '/groups',                //权限列表
  'wxPcLogin': '/oauth/wechat/pc/user',         // 微信登录列表

  'categories': '/categories',           //分类列表
  'categoriesDelete': '/categories',     //分类单个删除
  'categoriesBatchDelete': '/categories/batch',    //分类批量删除
  'createCategories': '/categories',               //创建分类
  'createBatchCategories': '/categories/batch',    //批量创建分类
  'categoriesBatchUpdate': '/categories/batch',    //批量修改分类

  'emojis': '/emoji',                    //表情接口
  'attachment': '/attachments',          //上传附件、图片
  'threads': '/threads',                 //主题
  'shareThreads': '/threads/share',
  'notice': '/notification',             //通知列表(回复,点赞,打赏)
  'wallet': '/wallet/user/',             //查看用户钱包
  'reflect': '/wallet/cash',            //提现记录列表
  'review': '/wallet/cash/review',       //提现审核
  'circleInfo': '/circleInfo',           //站点信息
  'themeNavListCon': '/themeNavListCon', //主题列表
  'walletFrozen': '/wallet/log',         //冻结金额
  'orderList': '/orders',                 //订单明细,打赏支付
  'orderPay': '/trade/pay/order',        //订单支付
  'order': '/order',                     //订单支付
  'walletDetails': '/wallet/log',       //钱包明细
  'updateWallet': '/wallet/user/',       //更新用户钱包
  'cash': '/wallet/cash',                //提现
  'collection': '/favorites',            //我的收藏
  // 'changePassword':'api/users/',     //修改密码
  'noticeList': '/notificationUnread',   //通知列表未读信息
  // 'searchUser':'/users',             //用户搜索
  'searchThreads': '/threads',           //搜索
  'notice': '/notification',
  'posts': '/posts',                     //回复审核修改-单条
  'postsBatch': '/posts/batch',          //回复主题修改-批量

  // 'getCircle': '/circle/getCircle',  //获取circle信息

  'pay': '/trade/pay/order',             //支付订单\
  'verifyPayPwd': '/users/pay-password/reset', //验证支付密码

  'threadsBatch': '/threads/batch',      //修改主题接口(批量)
  'upload': '/users/',                  //上传头像(原接口是'/users/{id}/avatar')
  'invite': '/invite',                   //创建邀请码
  'groups': '/groups',                   //获取所有操作类型、获取所有用户角色
  'groupPermission': '/permission',      //修改用户组权限
  'deleteNotification': '/notification', //删除通知里的回复我的
  'wechatDelete': '/users/',            //修改资料里的解绑微信
  'wechatBind': '/oauth/wechat/user',         //去绑定微信
  'postBatch': '/posts/batch/',          //删除回复接口[批量]

  'access': '/refresh-token',            //刷新token
  'follow': '/follow',                   //关注
  'realName': '/users/real',             //实名认证
  'signature': '/signature',				      //视频签名
  'weChatShare': '/offiaccount/jssdk',  //微信分享

  /*后台*/
  'siteinfo': '/siteinfo',               //首页-系统信息
  'settings': '/settings',               //设置接口
  'forum': '/forum',                     //获取前台配置接口
  'batchSubmit': '/stop-words/batch',    //创建敏感词接口[批量]
  'serachWords': '/stop-words',          //查询敏感词接口[列表]
  'exportWords': '/stop-words/export',   //导出敏感词
  'logo': '/settings/logo',              //上传站点logo
  'siteinfo': '/siteinfo',               //站点基本信息
  'deleteWords': '/stop-words/',         //删除敏感词
  'deleteAvatar': '/users',              //删除用户头像
  'exportUser': '/export/users?',        //用户信息导出
  'statistic': '/statistic/finance',     //获取资金概况
  'statisticChart': '/statistic/financeChart',     //获取盈利图表数据
  'noticeList': '/notification/tpl',               //通知设置列表
  'notification': '/notification/tpl/',   //修改系统消息模版[通知设置]
  'noticeConfigure': '/notification/tpl/', //通知配置列表,

  'wxPcUrl':'/oauth/wechat/web/user',     //获取微信pcUrl
  'wxLoginStatus':'/oauth/wechat/web/user/serach', //获取微信扫码用户状态
}
