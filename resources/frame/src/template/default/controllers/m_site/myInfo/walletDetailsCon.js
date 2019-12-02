
import walletDetailsHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import Panenl from '../../../view/m_site/common/panel';

export default {
  data:function () {
    return {
      walletDetailsList:{

      },
      type:{
        10:'提现冻结',
        11:'提现成功',
        12:'提现解冻',
        30:'注册收入',
        31:'打赏收入',
        32:'人工收入',
        50:'人工支出'
      }
    }
  },

  components:{
    walletDetailsHeader,
    Panenl
  },
  mounted(){
    this.walletDetails()
  },
  methods:{
    walletDetails(){
      this.appFetch({
        url:'walletDetails',
        method:'get'
      }).then((res)=>{
        this.walletDetailsList = res.data
      })
    }
  }
}
