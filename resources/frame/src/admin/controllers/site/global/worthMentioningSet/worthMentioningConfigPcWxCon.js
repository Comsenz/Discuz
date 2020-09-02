
import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      loginStatus:'default',   //default h5 applets pc
      tableData: [{
        name: '公众号配置',
        type: 'h5',
        description: '用户在微信内使用微信授权登录，需配置服务号',
        status:true,
        icon:'fuwuhao'
      }, {
        name: '小程序微信授权登录',
        type:'applets',
        description: '用户在小程序使用微信授权登录',
        status:false,
        icon:'iconxiaochengxu'
      }, {
        name: 'PC端微信扫码登录',
        type:'pc',
        description: '用户在PC的网页使用微信扫码登录',
        status:true,
        icon:'iconweixin'
      }]
    }
  },
  methods:{
    configClick(type){
      this.$router.push({path:'/admin/worth-mentioning/config',type:'h5'});

      /*switch (type){
        case 'h5':
          this.loginStatus = 'h5';
          break;
        case 'applets':
          this.loginStatus = 'applets';
          break;
        case 'pc':
          this.loginStatus = 'pc';
          break;
        default:
          this.loginStatus = 'default';
      }*/
    }
  }
  ,
  components:{
    Card,
    CardRow
  }
}
