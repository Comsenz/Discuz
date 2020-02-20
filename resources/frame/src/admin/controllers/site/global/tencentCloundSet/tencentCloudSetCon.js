
import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      tableData: [
        {
          name: '云api',
          type: 'qcloud_close',
          description: '配置云api的密钥后，才可使用腾讯云的各项服务和能力',
          status:'',
          icon:'iconAPI',
          setFlag: true
        }, {
          name: '图片内容安全',
          type:'qcloud_cms_image',
          description: '请先配置云API，开通腾讯云图片内容安全服务，并确保有对应套餐包',
          status:'',
          icon:'icontupian',
          setFlag: false
        }, {
          name: '文本内容安全',
          type:'qcloud_cms_text',
          description: '请先配置云API，开通腾讯云文本内容安全服务，并确保有对应套餐包',
          status:'',
          icon:'iconwenben',
          setFlag: false
        }, {
          name: '短信',
          type:'qcloud_sms',
          description: '请先配置云API，开通腾讯云短信服务，并确保腾讯云账户的短信额度充足',
          status:'',
          icon:'iconduanxin',
          setFlag: true
        }, {
          name: '实名认证',
          type:'qcloud_facdid',
          description: '请先配置云API，开通腾讯云的人脸核身服务，并确保有对应资源包',
          status:'',
          icon:'iconshimingrenzheng',
          setFlag: false
        },{
          name: '对象存储',
          type:'qcloud_cos',
          description: '请先配置云API，开通腾讯云的对象存储及数据万象服务，并确保有对应资源包',
          status:'',
          icon:'iconduixiangcunchu',
          setFlag: true
        }

      ]
    }
  },
  created(){
    this.tencentCloudStatus()
  },
  methods:{
    configClick(type){
      console.log(type);
      switch (type){
        case 'qcloud_close':
          this.$router.push({path:'/admin/tencent-cloud-config/cloud',query: {type:type}});
          break;
        case 'qcloud_sms':
          this.$router.push({path:'/admin/tencent-cloud-config/sms',query: {type:type}});
          break;
        case 'qcloud_cos':
          this.$router.push({path:'/admin/tencent-cloud-config/cos',query: {type:type}});
          break;
        default:
          this.loginStatus = 'default';
      }
    },
    tencentCloudStatus(){
      this.appFetch({
        url:'forum',
        method:'get',
        data:{}
      }).then(res=>{
        console.log(res);
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          if (res.readdata._data.qcloud.qcloud_close) {
            this.tableData[0].status = true
          } else {
            this.tableData[0].status = false
          }
          if (res.readdata._data.qcloud.qcloud_cms_image) {
            this.tableData[1].status = true
          } else {
            this.tableData[1].status = false
          }
          if (res.readdata._data.qcloud.qcloud_cms_text) {
            this.tableData[2].status = true
          } else {
            this.tableData[2].status = false
          }
          if (res.readdata._data.qcloud.qcloud_sms) {
            this.tableData[3].status = true
          } else {
            this.tableData[3].status = false
          }
          if (res.readdata._data.qcloud.qcloud_facdid) {
            this.tableData[4].status = true
          } else {
            this.tableData[4].status = false
          }
          if (res.readdata._data.qcloud.qcloud_cos) {
            this.tableData[5].status = true
          } else {
            this.tableData[5].status = false
          }
          console.log(this.tableData)
        }
      })
    },
    loginSetting(index,type,status){
      console.log(type)
      if(type == 'qcloud_close') {
        console.log('333333333333333333s')
        this.changeSettings('qcloud_close',status);
      } else if( type == 'qcloud_cms_image'){
        this.changeSettings('qcloud_cms_image',status);
      } else if(type == 'qcloud_cms_text') {
        this.changeSettings('qcloud_cms_text',status);
      }else if(type == 'qcloud_sms'){
        this.changeSettings('qcloud_sms',status);
      }else if(type == 'qcloud_facdid'){
        this.changeSettings('qcloud_facdid',status);
      }else if(type == 'qcloud_cos'){
        this.changeSettings('qcloud_cos',status);
      }


    },
    changeSettings(typeVal,statusVal){
      console.log(typeVal);
      console.log(statusVal+'ddd2345');
      //登录设置状态修改
      this.appFetch({
        url:'settings',
        method:'post',
        data:{
          "data":[
            {
             "attributes":{
              "key":typeVal,
              "value":statusVal,
              "tag": 'qcloud'
             }
            }
           ]

        }
      }).then(data=>{
        console.log(data);
        if (data.errors){
          this.$message.error(data.errors[0].code);
        }else {
          this.$message({
            message: '修改成功',
            type: 'success'
          });
          this.tencentCloudStatus();
        }
      }).catch(error=>{
        // cthis.$message.error('修改失败');
      })
    },

  },
  components:{
    Card,
    CardRow
  }
}
