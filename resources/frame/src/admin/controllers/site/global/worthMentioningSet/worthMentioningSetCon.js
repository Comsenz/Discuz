
import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      loginStatus:'default',   //default h5 applets pc
      tableData: [{
        name: 'H5微信授权登录',
        type: 'h5',
        description: '用户在电脑网页使用微信扫码登录或微信内的H5、小程序使用微信授权登录',
        status:true,
        icon:'iconH'
      }, {
        name: '小程序微信授权登录',
        type:'applets',
        description: '用户在电脑网页使用微信扫码登录或微信内的H5、小程序使用微信授权登录',
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

      console.log(type);

      switch (type){
        case 'h5':
          this.$router.push({path:'/admin/worth-mentioning-config/h5wx'});
          // this.loginStatus = 'h5';
          break;
        case 'applets':
          this.$router.push({path:'/admin/worth-mentioning-config/applets'});
          // this.loginStatus = 'applets';
          break;
        case 'pc':
          this.$router.push({path:'/admin/worth-mentioning-config/pcwx'});
          // this.loginStatus = 'pc';
          break;
        default:
          this.$router.push({path:'/admin/worth-mentioning-set'});
          // this.loginStatus = 'default';
      }
    }
  }
  ,
  components:{
    Card,
    CardRow
  }
}
