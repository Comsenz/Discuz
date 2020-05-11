import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      sdkAppId:'',
      appKey:'',
      smsId:'',
      smsSignature:'',//短信签名

    }
  },
  created(){
    var type = this.$route.query.type;
    this.type = type;
    this.tencentCloudSms()
  },
  methods:{
    tencentCloudSms(){
      this.appFetch({
        url:'forum',
        method:'get',
        data:{}
      }).then(res=>{
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          this.sdkAppId = res.readdata._data.qcloud.qcloud_sms_app_id;
          this.appKey = res.readdata._data.qcloud.qcloud_sms_app_key;
          this.smsId = res.readdata._data.qcloud.qcloud_sms_template_id;
          this.smsSignature = res.readdata._data.qcloud.qcloud_sms_sign;
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
                "key":'qcloud_sms_app_id',
                "value":this.sdkAppId,
                "tag": "qcloud"
              }
            },
            {
              "attributes":{
                "key":'qcloud_sms_app_key',
                "value":this.appKey,
                "tag": "qcloud",
              }
              },
              {
                "attributes":{
                  "key":'qcloud_sms_template_id',
                  "value":this.smsId,
                  "tag": "qcloud",
                }
              },{
                "attributes":{
                  "key":'qcloud_sms_sign',
                  "value":this.smsSignature,
                  "tag": "qcloud",
                }
              }

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
