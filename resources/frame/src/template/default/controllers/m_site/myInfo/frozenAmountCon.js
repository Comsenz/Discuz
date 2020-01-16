
import FrozenAmountHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import Panenl from '../../../view/m_site/common/panel';
import webDb from "../../../../../helpers/webDbHelper";

export default {
  data:function () {
    return {
      walletFrozenList:{},
      userId:''
    }
  },

  components:{
    FrozenAmountHeader,
    Panenl
  },
  mounted(){
    this.userId = webDb.getLItem('tokenId');
    this.walletFrozen()
  },
  methods:{
    walletFrozen(){
      this.appFetch({
        url:"walletFrozen",
        method:"get",
        data:{
          'filter[user]':this.userId,
          'filter[change_type]':10,
          include:''
        }
      }).then((res)=>{
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          // throw new Error(res.error)
        }else{
        this.walletFrozenList = res.data
        }
      })
    }
  }
}
