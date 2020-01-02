
import WithdrawHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import Panenl from '../../../view/m_site/common/panel';
import browserDb from '../../../../../helpers/webDbHelper';

export default {
  data:function () {
    return {
      payee:"",
      canWithdraw:'', //可提现余额
      withdrawalAmount:'',
      handlingFee:'',
      // actualCashWithdrawal:this.actual,
      phone:"", //绑定手机号
      bind:'verify',
      sms:'',
      show: false ,//数字键盘显示状态
      sendStatus:true, //发送验证码按钮
      time: 1, //发送验证码间隔时间
      insterVal: '',
      isGray: false,
      btnContent:'发送验证码',
      wechatNickname:'',
      mobileConfirmed:'',//验证验证码是否正确
    }
  },

  components:{
    WithdrawHeader,
    Panenl
  },
  created(){
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
      this.appFetch({
        url:'users',
        method:'get',
        splice:'/'+userId,
        data:{
          include:'wechat'
        }
      }).then(res=>{
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          // throw new Error(res.error)
        }else{
        console.log(res)
        this.payee= res.data.attributes.username;
        this.phone = res.data.attributes.mobile;
        if(res.readdata.wechat){
          console.log(res.readdata.wechat,'999999')
          this.wechatNickname = res.readdata.wechat._data.nickname //微信昵称
        }
      }
      })
     
      this.appFetch({
        url:'wallet',
        method:'get',
        splice:userId,
        data:{
          include:'',
        }
      }).then(res=>{
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          // throw new Error(res.error)
        }else{
        this.canWithdraw = res.data.attributes.available_amount;
         this.handlingFee = res.data.attributes.cash_tax_ratio;
        }
      })
    },
  

    sendVerificationCode(){
      if(this.canWithdraw == 0.00 || this.phone ==''){
        this.sendStatus = false
      }
      if(!this.wechatNickname){
        this.$toast('请绑定微信')
        return
      }
      var phone = this.phone
      if(!phone){
        this.$toast('请先绑定手机号')
        return
      }
      this.appFetch({
        url:'sendSms',
        method:'post',
        data:{
          "data": {
            "attributes": {
              // 'mobile':this.phone,
              'type':this.bind
            }
          }
        }
      }).then(res=>{
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          // throw new Error(res.error)
        }else{
        console.log(res)
        this.insterVal = res.data.attributes.interval;
          this.time = this.insterVal;
          this.timer();
        }
      })
    }, //发送验证码
    timer() {
      // alert('执行');
      if (this.time > 1) {
        // alert('2222');
        this.time--;
        this.btnContent = this.time + "s后重新获取";
        this.disabled = true;
        var timer = setTimeout(this.timer, 1000);
        this.isGray = true;
      } else if (this.time == 1) {
        this.btnContent = "获取验证码";
        clearTimeout(timer);
        this.disabled = false;
        this.isGray = false;
      }
    },

    withdraw(){
      var phone = this.phone
      if(!phone){
        this.$toast('请先绑定手机号')
        return
      }
      if(!this.wechatNickname){
        this.$toast('请绑定微信')
        return
      }
      this.appFetch({
        url:'cash',
        method:"post",
        data:{
          cash_apply_amount:this.withdrawalAmount
        }
      }).then((res)=>{
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          // throw new Error(res.error)
        }else{
        // this.actualCashWithdrawal = res.data.attributes.cash_actual_amount; //实际提现金额
        this.canWithdraw = res.data.attributes.cash_apply_amount; //用户申请提现的金额
        this.handlingFee = res.data.attributes.cash_charge;//提现手续费
        }
      })
       
      var withdrawalAmount = this.withdrawalAmount;
      var sms = this.sms;
      var canWithdraw = this.canWithdraw
      if(!withdrawalAmount){
        this.$toast('请输入提现金额')
        return
      }
      if(!sms){
        this.$toast('请输入验证码')
        return
      }
      if(canWithdraw == 0.00){
        this.$toast('可提现金额不足')
        return
      }
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
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          // throw new Error(res.error)
        }else{
        this.mobileConfirmed =res.readdata._data.mobileConfirmed;
        if(this.mobileConfirmed == true){
          this.$toast("提现申请已提交，请等待审核");
          // this.$router.push({path:'/modify-data'});
        }
      }
      })
    },
    
  }
}
