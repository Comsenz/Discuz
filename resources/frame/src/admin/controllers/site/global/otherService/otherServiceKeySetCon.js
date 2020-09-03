
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
          this.key = data.data.attributes.lbs.qq_lbs_key;
        }
      })
    },
    submitConfiguration(){
      if(!this.key) {
        this.$message({
          message: 'key不能为空',
          type: 'error'
        });
        return;
      }
      this.appFetch({
        url:'settings',
        method:'post',
        data:{
          "data":[
            {
               "attributes":{
                "key":"qq_lbs_key",
                "value":this.key,
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
            message: '提交成功',
            type: 'success'
          });
        }
      })
    }
  },
  components:{
    Card,
    CardRow
  }
}
