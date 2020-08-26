
import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      settingStatus:[{
          name: '腾讯位置服务',
          type: 'lbs_close',
          description: '配置KEY后，才可使用腾讯位置的WebServiceAPI服务',
          tag:'lbs',
          status:'',
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
          const lbsData = data.data.attributes.lbs;
          if (lbsData.lbs) {
            this.settingStatus[0].status = true;
          } else {
            this.settingStatus[0].status = false;
          }
        }
      })
    },
    statusSetting(statusVal){
      //状态修改
      this.appFetch({
        url:'settings',
        method:'post',
        data:{
         "data":[
            {
             "attributes":{
                "key":"lbs",
                "value":statusVal,
                "tag": 'lbs'
             }
            },
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
