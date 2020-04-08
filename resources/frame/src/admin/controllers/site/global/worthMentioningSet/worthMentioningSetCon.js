
import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      loginStatus:'default',   //default h5 applets pc
      settingStatus: [{
        name: '公众号接口配置',
        type: 'offiaccount_close',
        tag: 'wx_offiaccount',
        description: '用户在微信内使用微信授权登录',
        status:'',
        icon:'iconH'
      }, {
        name: '小程序微信授权登录',
        type:'miniprogram_close',
        tag: 'wx_miniprogram',
        description: '用户在小程序使用微信授权登录',
        status:'',
        icon:'iconxiaochengxu'
      }, {
        name: 'PC端微信扫码登录',
        type:'oplatform_close',
        tag: 'wx_oplatform',
        description: '用户在PC的网页使用微信扫码登录',
        status:'',
        icon:'iconweixin'
        }]
      // settingStatus:{}
    }
  },
  created:function(){
    this.loadStatus();
  },
  methods:{
    loadStatus(){
      //初始化登录设置状态
      this.appFetch({
        url:'forum',
        method:'get',
        data:{
        }
      }).then(data=>{
        if (data.errors){
          this.$message.error(res.errors[0].code);
        }else {

          if (data.readdata._data.passport.offiaccount_close == '0') {
            this.settingStatus[0].status = false;
          } else {
            this.settingStatus[0].status = true;
          }
          if (data.readdata._data.passport.miniprogram_close == '0') {
            this.settingStatus[1].status = false;
          } else {
            this.settingStatus[1].status = true;
          }
          if (data.readdata._data.passport.oplatform_close == '0') {
            this.settingStatus[2].status = false;
          } else {
            this.settingStatus[2].status = true;
          }
        }
        // this.$message({'修改成功'});
      }).catch(error=>{
      })
    },

    configClick(type){
      this.$router.push({
        path:'/admin/worth-mentioning-config/h5wx',
        query: {type:type}
      });
    },
    //修改配置状态
    loginSetting(index,type,status){
      if(type == 'offiaccount_close') {
        this.changeSettings('offiaccount_close',status,'wx_offiaccount');
      } else if( type == 'miniprogram_close'){
        this.changeSettings('miniprogram_close',status,'wx_miniprogram');
      } else {
        this.changeSettings('oplatform_close',status,'wx_oplatform');
      }
    },
    //修改配置时请求接口
    changeSettings(typeVal,statusVal,TagVal){
      //登录设置状态修改
      this.appFetch({
        url:'settings',
        method:'post',
        data:{
          "data":[
            {
             "attributes":{
              "key":typeVal,
              "value":statusVal,
              "tag": TagVal
             }
            }
           ]
        }
      }).then(data=>{
        if (data.errors){
          this.$message.error(data.errors[0].code);
        }else {
          this.$message({
            message: '修改成功',
            type: 'success'
          });
          this.loadStatus();
        }
      }).catch(error=>{
        this.$message.error('修改失败');
      })
    }
  }
  ,
  components:{
    Card,
    CardRow
  }
}
