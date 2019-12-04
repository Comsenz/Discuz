
import WithdrawHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import Panenl from '../../../view/m_site/common/panel';
import browserDb from '../../../../../helpers/webDbHelper';

export default {
  data:function () {
    return {
      payee:"",
      canWithdraw:'',
      withdrawalAmount:'',
      handlingFee:'',
      // actualCashWithdrawal:this.actual,
      phone:"",
      bind:'bind',
      sms:'',

      show: false //数字键盘显示状态
    }
  },

  components:{
    WithdrawHeader,
    Panenl
  },
  mounted(){
    this.withdrawUser()
  },
  computed:{
    actualCashWithdrawal(){
      return this.withdrawalAmount *this.handlingFee
    }
   
  },

  methods:{
    onInput(value) {
      this.withdrawalAmount = value;
      // this.actualCashWithdrawal = this.canWithdraw *this.handlingFee //实际提现金额
      console.log(value);
    },
    onDelete() {
      console.log('删除');
    },
    withdrawUser(){
      var userId = browserDb.getLItem('tokenId');
      this.apiStore.find('users',userId).then(res=>{
        this.payee= res.data.attributes.username;
        this.phone = res.data.attributes.mobile;
      })
      this.appFetch({
        url:'wallet',
        method:'get',
      }).then(res=>{
        this.canWithdraw = res.data.attributes.available_amount;
         this.handlingFee = res.data.attributes.cash_tax_ratio;
       
      })
    },
    withdraw(){
      this.appFetch({
        url:'cash',
        method:"post",
        data:{
          cash_apply_amount:this.withdrawalAmount
        }
      }).then((res)=>{
        // this.actualCashWithdrawal = res.data.attributes.cash_actual_amount; //实际提现金额
        this.canWithdraw = res.data.attributes.cash_apply_amount; //用户申请提现的金额
        this.handlingFee = res.data.attributes.cash_charge;//提现手续费
      })

      this.appFetch({  //提交后验证验证码
        url:'smsVerify',
        method:'post',
        data:{
          "data": {
            "attributes": {
              "mobile": this.phone,
              "code": this.sms,
               "type":this.bind,

            }
          }
        }
      }).then(res=>{

      })
    },

    sendVerificationCode(){
      this.appFetch({
        url:'sendSms',
        method:'post',
        data:{
          "data": {
            "attributes": {
              'mobile':this.phone,
              'type':this.bind
            }
          }
        }
      }).then(res=>{
        console.log(res)
      })
    } //发送验证码
  }
}
