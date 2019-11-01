
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

  methods:{
    onInput(value) {
      console.log(value);
    },
    onDelete() {
      console.log('删除');
    }
  }
}
