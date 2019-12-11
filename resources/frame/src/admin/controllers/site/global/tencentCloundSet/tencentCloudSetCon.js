
import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {

      tableData: [{
        name: '云api',
        type: 'cloud',
        description: '配置云api的密钥后，才可使用腾讯云的各项服务和能力',
        status:true,
        icon:'iconAPI'
      },, {
        name: '图片内容安全',
        type:'img',
        description: '使用腾讯云的图片内容安全服务。请先配置云API，并确保腾讯云账户的图片内容安全额度充足',
        status:true,
        icon:'icontupian'
      },{
        name: '文本内容安全',
        type:'text',
        description: '使用腾讯云的文本内容安全服务。请先配置云API，并确保腾讯云账户的文本内容安全额度充足',
        status:true,
        icon:'iconwenben'
      },
      {
        name: '短信',
        type:'sms',
        description: '使用腾讯云的短信服务。请先配置云API，并确保腾讯云账户的短信额度充足',
        status:true,
        icon:'iconduanxin'
      }
      ]
    }
  },
  methods:{
    configClick(type){

      console.log(type);

      switch (type){
        case 'cloud':
          this.$router.push({path:'/admin/tencent-cloud-config/cloud'});
          break;
        case 'sms':
          this.$router.push({path:'/admin/tencent-cloud-config/sms'});
          break;
        default:
          this.loginStatus = 'default';
      }
    }
  },
  components:{
    Card,
    CardRow
  }
}
