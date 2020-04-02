
import WithdrawHeader from '../../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import Panenl from '../../../../view/m_site/common/panel';
import browserDb from '../../../../../../helpers/webDbHelper';

export default {
  data: function () {
    return {
      payee: "",
      canWithdraw: '', //可提现余额
      withdrawalAmount: '',
      number: '',  //键盘输入的可提现金额
      handlingFee: '',
      // actualCashWithdrawal:this.actual,
      phone: "", //绑定手机号
      bind: 'verify',
      sms: '',
      show: false,//数字键盘显示状态
      sendStatus: true, //发送验证码按钮
      time: 1, //发送验证码间隔时间
      insterVal: '',
      isGray: false,
      btnContent: '发送验证码',
      wechatNickname: '',
      mobileConfirmed: '',//验证验证码是否正确
      handlingFee1: '',
      loading: false, //loading状态
      disabled: false, //按钮状态
    }
  },

  components: {
    WithdrawHeader,
    Panenl
  },
  created() {
    this.withdrawUser()
  },
  computed: {
    lingFee() {  //手续费
      let lingFee = Math.round(this.handlingFee1 * this.withdrawalAmount * 100) / 100;
      return `${lingFee}元 （${this.handlingFee}）`;
    },
    actualCashWithdrawal() {  //实际提现金额
      let lingFee = Math.round(this.handlingFee1 * this.withdrawalAmount * 100);
      return this.withdrawalAmount === '' ? '' : Math.round(this.withdrawalAmount * 100 - lingFee) / 100;
    }
  },
  methods: {
    withdrawInput(val) {
      this.handleReg();
    },
    formatter(value) {
      return this.handleReg(value);
    },
    onInput(value) {
      this.withdrawalAmount = this.handleReg(this.withdrawalAmount + value.toString());
    },
    handleReg(value) {
      value = value.toString(); // 先转换成字符串类型
      if (value.indexOf('.') == 0) {
        value = '0.';  // 第一位就是 .
      }
      value = value.replace(/[^\d.]/g, "");  //清除“数字”和“.”以外的字符
      value = value.replace(/\.{2,}/g, "."); //只保留第一个. 清除多余的
      value = value.replace(".", "$#$").replace(/\./g, "").replace("$#$", ".");
      value = value.replace(/^(\-)*(\d+)\.(\d\d).*$/, '$1$2.$3');//只能输入两个小数
      //以上已经过滤，此处控制的是如果没有小数点，首位不能为类似于 01、02的金额
      if (value.indexOf(".") < 0 && value != "") {
        value = parseFloat(value);
      }
      return value;
    },
    onDelete() {
      this.withdrawalAmount = this.withdrawalAmount.slice(0, -1);
    },
    withdrawUser() {
      var userId = browserDb.getLItem('tokenId');
      this.appFetch({
        url: 'users',
        method: 'get',
        splice: '/' + userId,
        data: {
          include: 'wechat'
        }
      }).then(res => {
        if (res.errors) {
          this.$toast.fail(res.errors[0].code);
          // throw new Error(res.error)
        } else {
          this.payee = res.data.attributes.username;
          this.phone = res.data.attributes.mobile;
          if (res.readdata.wechat) {
            this.wechatNickname = res.readdata.wechat._data.nickname //微信昵称
          }
        }
      })

      this.appFetch({
        url: 'wallet',
        method: 'get',
        splice: userId,
        data: {
          include: '',
        }
      }).then(res => {
        if (res.errors) {
          this.$toast.fail(res.errors[0].code);
          // throw new Error(res.error)
        } else {
          let tempVal = parseFloat(res.data.attributes.available_amount).toFixed(3);
          let realVal = tempVal.substring(0, tempVal.length - 1)
          this.canWithdraw = realVal //可提现金

          this.handlingFee = (res.data.attributes.cash_tax_ratio * 100) + '%';
          this.handlingFee1 = res.data.attributes.cash_tax_ratio
        }
      })
    },


    sendVerificationCode() { //发送验证吗
      if (this.canWithdraw == 0.00 || this.phone == '') {
        this.sendStatus = false
      }
      if (!this.wechatNickname) {
        this.$toast('请绑定微信')
        return
      }
      var phone = this.phone
      if (!phone) {
        this.$toast('请先绑定手机号')
        return
      }

      var withdrawalAmount = this.withdrawalAmount;
      var canWithdraw = this.canWithdraw
      if (!withdrawalAmount) {
        this.$toast('请输入提现金额')
        return
      }
      if (canWithdraw == 0.00) {
        this.$toast('可提现金额不足')
        return
      }
      this.appFetch({
        url: 'sendSms',
        method: 'post',
        data: {
          "data": {
            "attributes": {
              // 'mobile':this.phone,
              'type': this.bind
            }
          }
        }
      }).then(res => {
        if (res.errors) {
          this.$toast.fail(res.errors[0].detail[0]);
          // throw new Error(res.error)
        } else {
          this.insterVal = res.data.attributes.interval;
          this.time = this.insterVal;
          this.timer();
        }
      })
    }, //发送验证码
    timer() {
      if (this.time > 1) {
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

    withdraw() {   //提交
      var withdrawalAmount = this.withdrawalAmount;
      var sms = this.sms;
      var canWithdraw = this.canWithdraw
      if (!withdrawalAmount) {
        this.$toast('请输入提现金额')
        return
      }
      if (!sms) {
        this.$toast('请输入验证码')
        return
      }
      if (canWithdraw == 0.00) {
        this.$toast('可提现金额不足')
        return
      }
      this.loading = true;
      this.appFetch({  //提交后验证验证码
        url: 'smsVerify',
        method: 'post',
        data: {
          "data": {
            "attributes": {
              "mobile": this.phone,
              "code": this.sms,
              "type": this.bind,

            }
          }
        }
      }).then(res => {
        this.loading = false;
        if (res.errors) {
          this.$toast.fail(res.errors[0].code);
          this.loading = false;
          // throw new Error(res.error)
        } else {
          this.mobileConfirmed = res.readdata._data.mobileConfirmed;
          if (this.mobileConfirmed == true) {
            this.$toast("提现申请已提交，请等待审核");
            this.loading = false;
            this.$router.push({ path: '/' });  //提现成功后跳转到首页
          }
          var phone = this.phone
          if (!phone) {
            this.$toast('请先绑定手机号')
            return
          }
          if (!this.wechatNickname) {
            this.$toast('请绑定微信')
            return
          }
          this.loading = true;
          this.appFetch({
            url: 'cash',
            method: "post",
            data: {
              cash_apply_amount: this.withdrawalAmount
            }
          }).then((res) => {
            if (res.errors) {
              this.$toast.fail(res.errors[0].detail[0]);
              // this.$toast.fail(res.errors[0].code);
              this.loading = false;
              // throw new Error(res.error)
            } else {
              // this.actualCashWithdrawal = res.data.attributes.cash_actual_amount; //实际提现金额
              this.canWithdraw = res.data.attributes.cash_apply_amount; //用户申请提现的金额
              this.handlingFee = res.data.attributes.cash_charge;//提现手续费
              this.loading = false;
              // this.handlingFee1 = (this.handlingFee/100)
            }
          })
        }
      })
    },

  }
}
