
import Card from '../../../view/site/common/card/card';
import CardRow from '../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      loginStatus:'default',  //default yun dx
      tableData: [{
        name: '云api',
        type: 'yun',
        description: '配置云api的密钥后，才可使用腾讯云的各项服务和能力',
        status:true
      }, {
        name: '短信',
        type:'dx',
        description: '使用腾讯云的短信服务。请先配置云API，并确保腾讯云账户的短信额度充足',
        status:true
      }, {
        name: '图片内容安全',
        type:'',
        description: '使用腾讯云的图片内容安全服务。请先配置云API，并确保腾讯云账户的图片内容安全额度充足',
        status:true
      },{
        name: '文本内容安全',
        type:'',
        description: '使用腾讯云的文本内容安全服务。请先配置云API，并确保腾讯云账户的文本内容安全额度充足',
        status:true
      }
      ]
    }
  },
  methods:{
    configClick(type){

      console.log(type);

      switch (type){
        case 'yun':
          this.loginStatus = 'yun';
          break;
        case 'dx':
          this.loginStatus = 'dx';
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
