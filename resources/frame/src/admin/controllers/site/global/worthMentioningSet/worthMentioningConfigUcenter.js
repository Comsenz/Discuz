import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      valueAppid: '',
      valueUrl: '',
      valueKey: '',
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
          this.valueKey = data.readdata._data.ucenter.ucenter_key;
          this.valueUrl = data.readdata._data.ucenter.ucenter_url;
          this.valueAppid = data.readdata._data.ucenter.ucenter_appid;
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
               "key":"ucenter_appid",
               "value":this.valueAppid,
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
            {
              "attributes":{
               "key":"ucenter_key",
               "value":this.valueKey,
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