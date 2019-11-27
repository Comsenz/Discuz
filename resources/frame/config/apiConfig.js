/**
 * 接口配置文件
 */
// import browserDb from '../src/helpers/webDbHelper';
// var nn =require ('../src/helpers/webDbHelper.js');
// let userId = browserDb.getLItem('tokenId');

module.exports = {
	'getVote': '/commonvote/getvote', //获取单组投票数据
  'register': '/api/register',   //用户名注册
  'login':'/api/login',//用户名登录
  'users':'/api/users', //用户信息
  'forum':'/api/forum',
  'sendSms':'/api/sms/send',  //发送验证码
  'smsVerify':'/api/sms/verify',  //短信验证
  'weixin':'/api/oauth/weixin',  //微信接口
  'classify':'/api/classify',   //分类
  'emojis':'/api/emoji',      //表情接口
  'threads':'/api/threads',//主题
  'circleInfo':'/api/circleInfo', //站点信息
  'themeNavListCon':'/api/themeNavListCon', //主题列表
  // 'getCircle': '/circle/getCircle', //获取circle信息
}
