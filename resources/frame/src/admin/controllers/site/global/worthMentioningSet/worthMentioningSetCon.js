
import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      forums: '', // forum数据
      loginStatus:'default',   //default h5 applets pc
      settingStatus: [{
        name: '公众号配置',
        type: 'offiaccount_close',
        tag: 'wx_offiaccount',
        description: '用户在微信内使用微信授权登录，需配置服务号',
        status:'',
        icon:'fuwuhao'
      }, {
        name: '小程序配置',
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
      }, // {
      //   name: 'UCenter',
      //   type: 'ucenter_close',
      //   tag:'ucenter',
      //   description: '配置UCenter地址以及通信密钥',
      //   status: '',
      //   icon: 'iconucenter',
      // }
    ]
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
          this.forums = data.readdata._data;
          console.log(data,'这是初始化');
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
          if (data.readdata._data.ucenter.ucenter == false) {
            this.settingStatus[3].status = false;
          } else {
            this.settingStatus[3].status = true;
          }
        }
        // this.$message({'修改成功'});
      }).catch(error=>{
      })
    },

    configClick(type){
      if (type === 'ucenter') {
        this.$router.push({
          path:'/admin/worth-mentioning-config/ucenter',
          query: {type:type}
        });
      } else {
        this.$router.push({
          path:'/admin/worth-mentioning-config/h5wx',
          query: {type:type}
        });
      }
    },
    //修改配置状态
    loginSetting(index,type,status){
      console.log(type, status, '9999999')
      if(status == '1') {
        if(type == 'offiaccount_close'){
          if(!this.forums.passport.offiaccount_app_id || !this.forums.passport.offiaccount_app_secret){
            this.$message.error('请先填写配置再开启');
            return;
          }
        }
        if(type == 'miniprogram_close'){
          if(!this.forums.passport.miniprogram_app_id || !this.forums.passport.miniprogram_app_secret){
            this.$message.error('请先填写配置再开启');
            return;
          }
        }
      }

      if (type === 'ucenter_close') {
        if (!this.forums.ucenter.ucenter_appid || !this.forums.ucenter.ucenter_key || !this.forums.ucenter.ucenter_url){
          this.$message.error('请先填写配置再开启');
          return;
        }
      }

      if(type == 'offiaccount_close') {
        this.changeSettings('offiaccount_close',status,'wx_offiaccount');
      } else if( type == 'miniprogram_close'){
        this.changeSettings('miniprogram_close',status,'wx_miniprogram');
      } else if (type == 'ucenter_close') {
        this.changeSettings('ucenter', status == '1' ? true : false, 'ucenter');
      } else {
        if(status == '1') {
          if(!this.forums.passport.offiaccount_close){
            this.$message.error('请先开启公众号配置');
            return;
          }
        }
        this.changeSettings('oplatform_close', status, 'wx_oplatform');
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
