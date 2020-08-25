
import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      settingStatus:[{
          name: '腾讯位置服务',
          type: 'wxpay_close',
          description: '配置KEY后，才可使用腾讯位置的WebServiceAPI服务',
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
    statusSetting(typeVal,statusVal,TagVal,Tips){
      //状态修改
    //   this.appFetch({
    //     url:'settings',
    //     method:'post',
    //     data:{
    //       "data":[
    //         {
    //          "attributes":{
    //           "key":typeVal,
    //           "value":statusVal,
    //           "tag": TagVal
    //          }
    //         }
    //        ]

    //     }
    //   }).then(data=>{
    //     if (data.errors){
    //       this.$message.error(data.errors[0].code);
    //     }else {
    //       if (Tips == 'true') {
    //         this.$message({
    //           message: '修改成功',
    //           type: 'success'
    //         });
    //       }
    //       this.loadStatus();
    //     }
    //   }).catch(error=>{
    //     cthis.$message.error('修改失败');
    //   })
    },
    configClick(type){
      this.$router.push({
        path:'/admin/other-service-set-key',
        query: {type:type}
      });
    },
  },
  components:{
    Card,
    CardRow
  }
}
