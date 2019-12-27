
import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      appId:'',
      mchId:'',
      apiKey:'',
      appSecret:'',
      type:''
    }
  },

  created(){
    var type = this.$route.query.type;
    console.log(type);
    this.type = type;
    this.loadStatus();
  },
  methods:{
    loadStatus(){
      console.log('初始化');
      //初始化
      this.appFetch({
        url:'tags',
        method:'get',
        splice:'/'+this.type,
        data:{
        }
      }).then(data=>{
          console.log(data);
          this.appId = data.readdata[0]._data.app_id;
          this.mchId = data.readdata[0]._data.mch_id;
          this.apiKey = data.readdata[0]._data.api_key;
          this.appSecret = data.readdata[0]._data.app_secret;
      }).catch(error=>{
        // console.log('ʧ��');
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
                "key":"app_id",
                "value":this.appId,
                "tag": this.type
               }
            },
            {
               "attributes":{
                "key":"mch_id",
                "value":this.mchId,
                "tag": this.type
               }
            },
            {
               "attributes":{
                "key":"api_key",
                "value":this.apiKey,
                "tag": this.type
               }
            },
            {
               "attributes":{
                "key":"app_secret",
                "value":this.appSecret,
                "tag": this.type
               }
            }
           ]
        }
      }).then(data=>{
        // console.log(data)
        // this.$router.push({
        //   path:'/admin/pay-set'
        // });
        this.$message({
          message: '提交成功',
          type: 'success'
        });
      })
    }




    // configClick(){
    //   this.loginStatus = 'wx'
    // }
  },
  components:{
    Card,
    CardRow
  }
}
