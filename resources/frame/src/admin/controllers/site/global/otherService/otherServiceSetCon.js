
import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      settingStatus:[{
        name: '腾讯位置服务',
        type: 'lbs_close',
        description: '配置KEY后，才可使用腾讯位置的WebServiceAPI服务，<a href="https://discuz.com/manual-admin/2.html#_2-10-1-%E8%85%BE%E8%AE%AF%E4%BD%8D%E7%BD%AE%E6%9C%8D%E5%8A%A1" target="_blank">查看文档</a>',
        tag:'lbs',
        status:'',
      }],
      key: '',
    }
  },
  created:function(){
    this.loadStatus();
  },
  methods:{
    loadStatus(){
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
          this.key = data.data.attributes.lbs.qq_lbs_key;
          if (lbsData.lbs) {
            this.settingStatus[0].status = true;
          } else {
            this.settingStatus[0].status = false;
          }
        }
      })
    },
    statusSetting(statusVal){
      if(statusVal && !this.key) {
        this.$message.error('请先配置key');
        return;
      }
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
