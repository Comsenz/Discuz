import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      valueKey: '',
      valueUrl: '',
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
        console.log(data);
        if (data.errors){
          this.$message.error(data.errors[0].code);
        }else {
          this.valueKey = data.data.attributes.ucenter.ucenter_key;
          this.valueUrl = data.data.attributes.ucenter.ucenter_url;
        }
      })
    },
    submitConfiguration(){
      this.appFetch({
        url:'settings',
        method:'post',
        data:{
          "data":[
            {
               "attributes":{
                "key":"ucenter_key",
                "value":this.valueKey,
                "tag": 'ucenter'
               }
            },
            {
              "attributes":{
               "key":"ucenter_url",
               "value":this.valueUrl,
               "tag": 'ucenter'
              }
            },
          ]
        }
      }).then(data=>{
        console.log(data);
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