
import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      key:'',
    }
  },

  created(){
    var type = this.$route.query.type;
    this.type = type;
    this.loadStatus();
  },
  methods:{
    loadStatus(){
      //初始化
      this.appFetch({
        url:'forum',
        method:'get',
        data:{}
      }).then(data=>{
        if (data.errors){
          this.$message.error(data.errors[0].code);
        }else {
          this.key = data.readdata._data.paycenter.app_id;
        }
      }).catch(error=>{
      })
    },
    submitConfiguration(){

      // this.appFetch({
      //   url:'settings',
      //   method:'post',
      //   data:{
      //     "data":[
      //       {
      //          "attributes":{
      //           "key":"app_id",
      //           "value":this.appId,
      //           "tag": this.type
      //          }
      //       },
      //      ]
      //   }
      // }).then(data=>{
      //   if (data.errors){
      //     this.$message.error(data.errors[0].code);
      //   }else {
      //     this.$message({
      //       message: '提交成功',
      //       type: 'success'
      //     });
      //   }
      // })
    }
  },
  components:{
    Card,
    CardRow
  }
}
