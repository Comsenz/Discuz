/**
 * 接口配置文件
 */
var browserDb =require ('../src/helpers/webDbHelper.js');

module.exports = {
	'getVote': '/commonvote/getvote', //获取单组投票数据
  'register': '/api/register',   //用户名注册
  'login':'/api/login',//用户名登录
  'users':'/api/users', //用户信息
  'forum':'/api/forum', //站点信息
  'sendSms':'/api/sms/send',  //发送验证码
  'smsVerify':'/api/sms/verify',  //短信验证
  'weixin':'/api/oauth/weixin',  //微信接口
  'classify':'/api/classify',   //分类
  'emojis':'/api/emoji',      //表情接口
  'threads':'/api/threads',//主题
  'notice':'/api/notification',//通知列表(回复,点赞,打赏)
  'wallet':`/api/wallet/user/${browserDb.default.getLItem('tokenId')}`,//查看用户钱包
  'reflect':'/api/wallet/cash' ,//提现记录列表
  'circleInfo':'/api/circleInfo', //站点信息
  'themeNavListCon':'/api/themeNavListCon', //主题列表
  'walletFrozen':'/api/wallet/log',//冻结金额
  'orderList':'/api/order',//订单明细
  'invite':'/api/invite',
  'walletDetails':'/api/wallet/log' ,//钱包明细
  'updateWallet':'/api/wallet/user/',//更新用户钱包
  'cash':'/api/wallet/cash',//提现
  'collection':'/api/favorites',//我的收藏
  'changePassword':`/api/users/${browserDb.default.getLItem('tokenId')}`,//修改密码
  'noticeList':'/api/notificationUnread', //通知列表未读信息
  'searchUser':'/api/users', //用户搜索
  'searchThreads':'/api/threads',//搜索
  'notice':'/api/notification',
  // 'getCircle': '/circle/getCircle', //获取circle信息


  /*后台*/
  'siteinfo':'/api/siteinfo'    //首页-系统信息

}
