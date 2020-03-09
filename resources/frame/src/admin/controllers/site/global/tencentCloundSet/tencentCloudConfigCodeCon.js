import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      appId:'',     //APPID：
      secretId:'',  //App Secret Key：
    }
  },
  created(){
    var type = this.$route.query.type;
    this.type = type;
    this.tencentCloudCode()
  },
  methods:{
    tencentCloudCode(){
      this.appFetch({
        url:'forum',
        method:'get',
        data:{}
      }).then(res=>{
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          this.appId = res.readdata._data.qcloud.qcloud_captcha_app_id;
          this.secretId = res.readdata._data.qcloud.qcloud_captcha_secret_key;
        }
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
                "key":'qcloud_captcha',
                "value":1,
                "tag": "qcloud"
              }
            },
            {
              "attributes":{
                "key":'qcloud_captcha_app_id',
                "value":this.appId,
                "tag": "qcloud"
              }
            },
            {
              "attributes":{
                "key":'qcloud_captcha_secret_key',
                "value":this.secretId,
                "tag": "qcloud",
              }
              },
          ]
        }
      }).then(res=>{
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          this.$message({message: '提交成功', type: 'success'});
        }
      })
    }

  },
  components:{
    Card,
    CardRow
  }
}
