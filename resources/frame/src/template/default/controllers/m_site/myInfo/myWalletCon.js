/**
 * 我的钱包
 */

import myWalletHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import browserDb from '../../../../../helpers/webDbHelper';
// import ContHeader from '../../../view/m_site/common/cont/contHeaderView'
// import ContMain from '../../../view/m_site/common/cont/contMainView'
// import ContFooter from '../../../view/m_site/common/cont/contFooterView'


export default {
  data:function () {
    return {
      // imgUrl:'',
      // stateTitle:'点赞了我',
      // time:"5分钟前",
      // userName:'Elizabeth'
      value:'',
      valueFrozen:'',
      user_id:'1'
    }
  },
  components:{
    myWalletHeader
    // ContHeader,
    // ContMain,
    // ContFooter
  },
 
  created(){
    this.wallet()
  },
  mounted(){
    this.wallet()
  },
  methods:{
    myWallet(str){
      switch (str) {
        case 'frozen-amount':
          this.$router.push('/frozen-amount'); //冻结金额
          break;
        case 'withdrawals-record':
          this.$router.push('/withdrawals-record'); //提现记录
          break;
        default:
          this.$router.push('/');
      }
    },
    wallet(){
      // const tokenId = browserDb.getLItem('tokenId');
      this.appFetch({
        url:'wallet',
        method:'get',
        data:{
          // user_id:this.user_id
        }
      },(res)=>{
        if(res == '200'){
          console.log('成功')
        }else{
          console.log('400')
        }
      }).then((res)=>{
        this.value = res.data.attributes.available_amount;
        this.valueFrozen = res.data.attributes.freeze_amount;
      })
      // let user_id = browserDb.getLItem('tokenId');
      // this.apiStore.find('wallet/user',user_id).then(res=>{
      //   this.value = res.data.attributes.available_amount;
      //   this.valueFrozen = res.data.attributes.freeze_amount;
      // })
    }
  },
}
