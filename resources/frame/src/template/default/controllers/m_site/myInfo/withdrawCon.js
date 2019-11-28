
import WithdrawHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import Panenl from '../../../view/m_site/common/panel';

export default {
  data:function () {
    return {
      payee:"林夕",
      canWithdraw:'100元',
      withdrawalAmount:'',
      handlingFee:'3%',
      actualCashWithdrawal:'97元',
      phone:"18845****23",

      show: false //数字键盘显示状态
    }
  },

  components:{
    WithdrawHeader,
    Panenl
  },
  mounted(){
    this.withdraw()
  },

  methods:{
    onInput(value) {
      console.log(value);
    },
    onDelete() {
      console.log('删除');
    },
    withdraw(){
      this.appFetch({
        url:'cash',
        method:"post",
        data:{
          cash_apply_amount:'1'
        }
      }).then((res)=>{
        this.actualCashWithdrawal = res.data.attributes.cash_actual_amount; //实际提现金额
        this.canWithdraw = res.data.attributes.cash_apply_amount; //用户申请提现的金额
      })

    }
  }
}
