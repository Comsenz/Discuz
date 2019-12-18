/**
 * 接口配置文件
 */

module.exports = {
  'getVote': '/commonvote/getvote', //获取单组投票数据
  'register': '/api/register',   //用户名注册
  'login':'/api/login',//用户名登录
  'users':'/api/users', //用户信息
  'forum':'/api/forum', //站点信息
  'sendSms':'/api/sms/send',  //发送验证码
  'smsVerify':'/api/sms/verify',  //短信验证
  'weixin':'/api/oauth/weixin',  //微信接口

  'categories':'/api/categories',   //分类列表
  'categoriesDelete':'/api/categories',    //分类单个删除
  'categoriesBatchDelete':'/api/categories/batch',    //分类批量删除
  'createCategories':'/api/categories',   //创建分类
  'categoriesBatchUpdate':'/api/categories/batch',   //批量修改分类

  'emojis':'/api/emoji',      //表情接口
  'attachment':'/api/attachment', //上传附件、图片
  'threads':'/api/threads',//主题
  'notice':'/api/notification',//通知列表(回复,点赞,打赏)
  'wallet':'/api/wallet/user/',//查看用户钱包
  'reflect':'/api/wallet/cash' ,//提现记录列表
  'review':'/api/wallet/cash/review',     //提现审核
  'circleInfo':'/api/circleInfo', //站点信息
  'themeNavListCon':'/api/themeNavListCon', //主题列表
  'walletFrozen':'/api/wallet/log',//冻结金额
  'orderList':'/api/order',//订单明细,打赏支付
  'orderPay':'/api/trade/pay/order',  //订单支付
  'walletDetails':'/api/wallet/log' ,//钱包明细
  'updateWallet':'/api/wallet/user/',//更新用户钱包
  'cash':'/api/wallet/cash',//提现
  'collection':'/api/favorites',//我的收藏
  // 'changePassword':'api/users/',//修改密码
  'noticeList':'/api/notificationUnread', //通知列表未读信息
  // 'searchUser':'/api/users', //用户搜索
  'searchThreads':'/api/threads',//搜索
  'notice':'/api/notification',
  'posts':'/api/posts', //回复审核修改-单条
  'postsBatch':'/api/posts/batch',  //回复主题修改-批量

  // 'getCircle': '/circle/getCircle', //获取circle信息

  'pay':'/api/trade/pay/order',    //支付订单

  'threadsBatch':'/api/threads/batch',   //修改主题接口(批量)
  'upload': '/api/users/',//上传头像
  'invite':'/api/invite',//创建邀请码
  'groups':'/api/groups', //获取所有操作类型、获取所有用户角色
  'group-permission':'/api/group-permission', // 修改用户组权限
  'deleteNotification':'/api/notification', //删除通知里的回复我的
  'wechat':'/api/users/' ,//修改资料里的解绑微信
  'wechatBind':'api/oauth/wechat',//去绑定微信


  /*后台*/
  'siteinfo':'/api/siteinfo',    //首页-系统信息
  'settings':'/api/settings',    //设置接口
  'forum':'/api/forum',          //获取前台配置接口
  'batchSubmit':'/api/stop-words/batch', //创建敏感词接口[批量]
  'serachWords':'/api/stop-words', //查询敏感词接口[列表]
  'exportWords':'/api/stop-words/export',//导出敏感词
  'logo':'/api/settings/logo',   //上传站点logo
  'siteinfo':'api/siteinfo',  //站点基本信息
  'users':'/api/users',       //用户信息
 'deleteWords':'/api/stop-words/',//删除敏感词

}
