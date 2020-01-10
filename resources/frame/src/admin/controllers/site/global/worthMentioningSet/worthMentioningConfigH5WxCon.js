
import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      loginStatus:'default',   //default h5 applets pc
      appId:'',
      appSecret:'',
      type:'',
      typeCopywriting:{
        wx_offiaccount:{
          title:'公众号接口配置',
          appIdDescription:'填写申请公众号后，你获得的APPID ',
          appSecretDescription:'填写申请公众号后，你获得的App secret',
          url:'https://mp.weixin.qq.com/'
        },
        wx_miniprogram:{
          title:'小程序微信授权登录设置',
          appIdDescription:'填写申请小程序后，你获得的APPID ',
          appSecretDescription:'填写申请小程序后，你获得的App secret',
          url:''
        },
        wx_oplatform:{
          title:'PC端微信扫码登录',
          appIdDescription:'填写申请PC端微信扫码后，你获得的APPID ',
          appSecretDescription:'填写申请PC端微信扫码后，你获得的App secret',
          url:''
        }

      },
    }
  },
  created(){
    var type = this.$route.query.type;
    this.type = type;
    this.loadStatus();
  },
  methods:{
    loadStatus(){
      console.log(this.type);
      this.appFetch({
        url:'tags',
        method:'get',
        splice:'/'+this.type,
        data:{
        }
      }).then(data=>{
          // console.log(data);
        if (data.errors){
          this.$message.error(data.errors[0].code);
        }else {
          this.appId = data.readdata[0]._data.app_id;
          this.appSecret = data.readdata[0]._data.app_secret;
        }
      }).catch(error=>{
        // console.log('ʧ��');
      })
    },
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
        if (data.errors){
          this.$toast.fail(data.errors[0].code);
        }else {
          // this.$router.push({
          //   path: '/admin/worth-mentioning-set'
          // });
          this.$message({
            message: '提交成功',
            type: 'success'
          });
        }
      })
    }
  },
  components:{
    Card,
    CardRow
  }
}
