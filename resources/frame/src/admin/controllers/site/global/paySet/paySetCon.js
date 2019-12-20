
import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      settingStatus:[{
          name: '微信支付',
          type: 'wxpay_close',
          description: '用户在电脑网页使用微信扫码支付 或  微信外的手机浏览器、微信内h5、小程序使用微信支付',
          tag:'wxpay',
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
        // console.log(data);
        if(data.readdata._data.wxpay_close == '0'){
          this.settingStatus[0].status = false;
        } else {
          this.settingStatus[0].status = true;
        }
      })
    },
    loginSetting(index,type,status){
      if(type == 'wxpay_close') {
        this.changeSettings('wxpay_close',status,'wxpay');
      }
    },
    changeSettings(typeVal,statusVal,TagVal){
      // console.log(statusVal);
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
