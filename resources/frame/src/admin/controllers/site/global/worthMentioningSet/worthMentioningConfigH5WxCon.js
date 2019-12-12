
import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      loginStatus:'default',   //default h5 applets pc
      appId:'',
      appSecret:'',
      type:''
      // tableData: [{
      //   name: 'H5微信授权登录',
      //   type: 'h5',
      //   description: '用户在电脑网页使用微信扫码登录或微信内的H5、小程序使用微信授权登录',
      //   status:true,
      //   icon:'iconH'
      // }, {
      //   name: '小程序微信授权登录',
      //   type:'applets',
      //   description: '用户在电脑网页使用微信扫码登录或微信内的H5、小程序使用微信授权登录',
      //   status:false,
      //   icon:'iconxiaochengxu'
      // }, {
      //   name: 'PC端微信扫码登录',
      //   type:'pc',
      //   description: '用户在PC的网页使用微信扫码登录',
      //   status:true,
      //   icon:'iconweixin'
      // }]
    }
  },
  created(){
    var type = this.$route.query.type;
    console.log(type);
    this.type = type;
  },
  methods:{
    // configClick(type){
    //   console.log(type);
    //   this.$router.push({path:'/admin/worth-mentioning/config',type:'h5'});

    //   /*switch (type){
    //     case 'h5':
    //       this.loginStatus = 'h5';
    //       break;
    //     case 'applets':
    //       this.loginStatus = 'applets';
    //       break;
    //     case 'pc':
    //       this.loginStatus = 'pc';
    //       break;
    //     default:
    //       this.loginStatus = 'default';
    //   }*/
    // },
    submitConfiguration(){
      this.appFetch({
        url:'settings',
        method:'post',
        data:{
          "data":[
            {
               "attributes":{
                "key":"app_id",
                "value":this.appId,
                "tag": this.type
               }
             },
             {
               "attributes":{
                "key":"app_secret",
                "value":this.appSecret,
                "tag": this.type
               }
            }
           ]
        }
      }).then(data=>{
        // console.log(data)
        this.$router.push({
          path:'/admin/worth-mentioning-set'
        });
        this.$message({
          message: '提交成功',
          type: 'success'
        });
      })
    }
  },
  components:{
    Card,
    CardRow
  }
}
