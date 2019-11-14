
import Card from '../../../view/site/common/card/card';
import CardRow from '../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      loginStatus:'default',  //wx
      tableData:[{
          name: '微信支付',
          type: 'h5',
          description: '用户在电脑网页使用微信扫码登陆或微信内的h5、小程序使用微信授权登陆',
          status:true
        }]
    }
  },
  methods:{
    configClick(){
      this.loginStatus = 'wx'
    }
  },
  components:{
    Card,
    CardRow
  }
}
