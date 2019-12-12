
import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      settingStatus:[{
          name: '微信支付',
          type: 'wechat_h5',
          description: '用户在电脑网页使用微信扫码支付 或  微信外的手机浏览器、微信内h5、小程序使用微信支付',
          status:''
        }]
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
          // this.settingStatus[0].status = false;
          this.settingStatus[0].status = true;
        }
      })
    },
    loginSetting(index,type,status){
      // console.log('提示：'+status);
      if(type == 'wechat_h5') {
        this.changeSettings('wechat_h5',status);
      } else if( type == 'wechat_min'){
        this.changeSettings('wechat_min',status);
      } else {
        this.changeSettings('wechat_pc',status);
      }
    },
    changeSettings(typeVal,statusVal){
      console.log(statusVal);
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
    },
    configClick(type){
      this.$router.push({
        path:'/admin/pay-config/wx',
        query: {type:type}
      });
    },

  },
  components:{
    Card,
    CardRow
  }
}
