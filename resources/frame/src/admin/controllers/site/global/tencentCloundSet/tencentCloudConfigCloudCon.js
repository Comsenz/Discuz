
/**云API配置 */
import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      secretId:'',
      secretKey:'',
      appId:'',
      type:''
    }
  },
  created(){
    this.tencentCloudList()//初始化云API配置
    var type = this.$route.query.type;
    this.type = type;
  },
  methods:{
    configClick(type){

    },
    tencentCloudList(){
      this.appFetch({
        url:'forum',
        method:'get',
        data:{

        }
      }).then(res=>{
        console.log(res);
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          this.appId = res.readdata._data.qcloud.qcloud_app_id
          this.secretId = res.readdata._data.qcloud.qcloud_secret_id
          this.secretKey = res.readdata._data.qcloud.qcloud_secret_key
        }
      })
    },
    async  Submission(){
      try{
        await this.appFetch({
        url:'settings',
        method:'post',
        data:{
          "data":[
            {
              "attributes":{
                "key":'qcloud_app_id',
                "value":this.appId,
                "tag": "qcloud"
              }
            },
            {
              "attributes":{
                "key":'qcloud_secret_id',
                "value":this.secretId,
                "tag": "qcloud",
              }
              },
              {
                "attributes":{
                  "key":'qcloud_secret_key',
                  "value":this.secretKey,
                  "tag": "qcloud",
                }
              }

          ]
        }
      }).then(res=>{
        if(res.errors){
          throw new Error(res.errors[0].code);
        }
          this.$message({ message: '提交成功', type: 'success' });
      })
    }
      catch(err){
        console.log(err)
        this.$message({
          showClose: true,
          message: '提交失败！'
        });
        // this.$message.error('操作失败！');
      }
  }
  },
  components:{
    Card,
    CardRow
  }
}
