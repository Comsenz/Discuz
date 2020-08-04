
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
          status:'',
          siteMode: '', // 站点模式
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
        if (data.errors){
          this.$message.error(data.errors[0].code);
        }else {
          this.siteMode = data.readdata._data.set_site.site_mode;
          if (data.readdata._data.paycenter.wxpay_close == '0') {
            this.settingStatus[0].status = false;
          } else {
            this.settingStatus[0].status = true;
          }
        }
      })
    },
    loginSetting(index,type,status){
      if (status == 0 && this.siteMode == 'pay') {
        this.$confirm('您当前开启了付费模式，若关闭微信支付，站点模式将切换为公开模式，若您在用户角色中设置了允许发布付费内容，关闭微信支付服务将同时清空该设置', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          if(type == 'wxpay_close') {
            this.changeSettings('wxpay_close',status,'wxpay','true');
            this.siteMode = 'public';
            this.changeSettings('site_mode',this.siteMode,'default','false');
          }
        })
      } else if (status == 0 && this.siteMode == 'public') {
        this.$confirm('若您在用户角色中设置了允许发布付费内容，关闭微信支付服务将同时清空该设置', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          if(type == 'wxpay_close') {
            this.changeSettings('wxpay_close',status,'wxpay','true');
          }
        })
      } else {
        if(type == 'wxpay_close') {
          this.changeSettings('wxpay_close',status,'wxpay', 'true');
        }
      }
    },
    changeSettings(typeVal,statusVal,TagVal,Tips){
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
          if (Tips == 'true') {
            this.$message({
              message: '修改成功',
              type: 'success'
            });
          }
          this.loadStatus();
        }
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
