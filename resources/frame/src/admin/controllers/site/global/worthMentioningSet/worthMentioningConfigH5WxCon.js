
import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      loginStatus:'default',   //default h5 applets pc
      appId:'',
      appSecret:'',
      type:''
    }
  },
  created(){
    var type = this.$route.query.type;
    this.type = type;
  },
  methods:{
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
                "key":"app_secret",
                "value":this.appSecret,
                "tag": this.type
               }
            }
          ]
        }
      }).then(data=>{
        this.$router.push({
          path:'/admin/worth-mentioning-set'
        });
        this.$message({
          message: '提交成功',
          type: 'success'
        });
      })
    }
  },
  components:{
    Card,
    CardRow
  }
}
