
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
    console.log(this.type);
    this.loadStatus();
  },
  methods:{
    loadStatus(){
      console.log(this.type);
      //初始化配置参数
      this.appFetch({
        url:'tags',
        method:'get',
        splice:'/'+this.type,
        data:{
        }
      }).then(data=>{
          // console.log(data);
          this.appId = data.readdata[0]._data.app_id;
          this.appSecret = data.readdata[0]._data.app_secret;
      }).catch(error=>{
        // console.log('失败');
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
          message: '鎻愪氦鎴愬姛',
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
