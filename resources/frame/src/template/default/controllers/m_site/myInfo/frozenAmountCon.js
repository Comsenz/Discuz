
import FrozenAmountHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import Panenl from '../../../view/m_site/common/panel';

export default {
  data:function () {
    return {
      user_id:'1',
      walletFrozenList:{
        // date:'2019-08-10 20:30'
      }

    }
  },

  components:{
    FrozenAmountHeader,
    Panenl
  },
  mounted(){
    this.walletFrozen()
  },
  methods:{
    walletFrozen(){
      this.appFetch({
        url:"walletFrozen",
        method:"get",
        data:{
          type:'10',
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
