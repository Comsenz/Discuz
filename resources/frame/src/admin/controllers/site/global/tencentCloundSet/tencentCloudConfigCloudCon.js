
/**云api配置 */
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
    this.tencentCloudList()//初始化云api配置
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
        console.log(res)
        this.appId = res.readdata._data.qcloud.qcloud_app_id
        this.secretId = res.readdata._data.qcloud.qcloud_secret_id
        this.secretKey = res.readdata._data.qcloud.qcloud_secret_key
      })
    },
    Submission(){
      this.appFetch({
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
        if(errors.status == 500){
          this.$message({ message: '提交失败',type: 'errors'});
        }
        this.$message({ message: '提交成功', type: 'success' });
      }).catch(err=>{
        console.log('500啦')
        this.$message({ message: '提交失败',type: 'errors'});
      })
    }
  },
  components:{
    Card,
    CardRow
  }
}
