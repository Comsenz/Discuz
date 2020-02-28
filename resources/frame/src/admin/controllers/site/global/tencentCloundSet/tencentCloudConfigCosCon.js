
/*
* 腾讯云设置：对象存储管理器
* */

import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      cosName:'',
      cosArea:'',
      cosDomainName:''
    }
  },
  methods:{
    submission(){
      this.appFetch({
        url:'settings',
        method:'post',
        data:{
          "data":[
            {
              "attributes":{
                "key":'qcloud_cos_bucket_name',
                "value":this.cosName,
                "tag": "qcloud"
              }
            },
            {
              "attributes":{
                "key":'qcloud_cos_bucket_area',
                "value":this.cosArea,
                "tag": "qcloud",
              }
            },
            {
              "attributes":{
                "key":'qcloud_ci_url',
                "value":this.cosDomainName,
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
    },

    //接口请求
    getTencentCloudCon(){
      this.appFetch({
        url:'forum',
        method:'get',
        data:{}
      }).then(res=>{
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          this.cosName = res.readdata._data.qcloud.qcloud_cos_bucket_name;
          this.cosArea = res.readdata._data.qcloud.qcloud_cos_bucket_area;
          this.cosDomainName = res.readdata._data.qcloud.qcloud_ci_url;
        }
      })
    },
  },
  created(){
    this.getTencentCloudCon();
  },
  components:{
    Card,
    CardRow
  }
}
