
import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      loginStatus:'default',   //default h5 applets pc
      settingStatus: [{
        name: '公众号接口配置',
        type: 'wechat_h5',
        description: '用户在微信内使用微信授权登录',
        status:'',
        icon:'iconH'
      }, {
        name: '小程序微信授权登录',
        type:'wechat_min',
        description: '用户在小程序使用微信授权登录',
        status:'',
        icon:'iconxiaochengxu'
      }, {
        name: 'PC端微信扫码登录',
        type:'wechat_pc',
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
        if(data.readdata._data.wechat_h5){
          this.settingStatus[0].status = true;
        } else {
          this.settingStatus[0].status = false;
        }
        if(data.readdata._data.wechat_min){
          this.settingStatus[1].status = true;
        } else {
          this.settingStatus[1].status = false;
        }
        if(data.readdata._data.wechat_pc){
          this.settingStatus[2].status = true;
        } else {
          this.settingStatus[2].status = false;
        }
        // this.$message('提交成功');
      }).catch(error=>{
        // console.log('失败');
      })
    },

    configClick(type){
      this.$router.push({
        path:'/admin/worth-mentioning-config/h5wx',
        query: {type:type}
      });
    },
    loginSetting(index,type,status){
      if(type == 'wechat_h5') {
        this.changeSettings('wechat_h5',status);
      } else if( type == 'wechat_min'){
        this.changeSettings('wechat_min',status);
      } else {
        this.changeSettings('wechat_pc',status);
      }
    },
    changeSettings(typeVal,statusVal){
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
              "tag": typeVal
             }
            }
           ]

        }
      }).then(data=>{
        // console.log(data)
        this.$message({
          message: '修改成功',
          type: 'success'
        });
        this.loadStatus();
      }).catch(error=>{
        cthis.$message.error('修改失败');
      })
    }
  }
  ,
  components:{
    Card,
    CardRow
  }
}
