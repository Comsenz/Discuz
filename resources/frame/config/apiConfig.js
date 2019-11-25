/**
 * 接口配置文件
 */

module.exports = {
	'getVote': '/commonvote/getvote', //获取单组投票数据
  'users': '/api/users',   //用户名注册
  'login':'/api/login',//用户名登录
  'sendSms':'/api/sms/send',  //发送验证码
  'smsVerify':'/api/sms/verify',  //短信验证
  'weixin':'/api/oauth/weixin',  //微信接口
  'threads':'/api/threads',//主题
  'circleInfo':'/api/circleInfo', //站点信息
  'themeNavListCon':'/api/themeNavListCon', //主题列表
  // 'getCircle': '/circle/getCircle', //获取circle信息
}
