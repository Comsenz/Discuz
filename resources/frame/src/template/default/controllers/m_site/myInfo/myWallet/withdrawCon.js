
import WithdrawHeader from '../../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import Panenl from '../../../../view/m_site/common/panel';
import browserDb from '../../../../../../helpers/webDbHelper';

export default {
  data: function () {
    return {
      payee: "",
      canWithdraw: '', //可提现余额
      withdrawalAmount: '',
      number: '',
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
      return `${this.handlingFee1 * this.withdrawalAmount}元 （${this.handlingFee}）`;
    },
    actualCashWithdrawal() {  //实际提现金额
      return this.withdrawalAmount === '' ? '' : this.withdrawalAmount - this.handlingFee1 * this.withdrawalAmount
    }

  },

  methods: {
    withdrawInput(val) {
      this.handleReg();
    },
    onInput(value) {
      this.withdrawalAmount = this.withdrawalAmount + '' + value;
      // const realVal = parseFloat(value).toFixed(2);
      this.handleReg();
    },
    handleReg() {
      // if(this.withdrawalAmount === '.'){                // 如果只输入一个点  变成 0.
      //   this.withdrawalAmount = '0.';
      //   return;
      // }
      const numF = parseFloat(this.withdrawalAmount);
      // if(isNaN(numF)){                                  // 如果输入的小数点后面有不是数字的部分
      //   this.withdrawalAmount = '';
      //   return;
      // }
      const num = Number(this.withdrawalAmount);
      // if(num > Number.MAX_SAFE_INTEGER){                // 输入的值超出了js的最大安全数
      //   this.withdrawalAmount = '';
      //   return;
      // }
      const whthDiawArr = this.withdrawalAmount.split('.');

      // if(whthDiawArr.length > 2){                        // 输入了超过两个小数点 只保留前面的一个小数点
      //   this.withdrawalAmount = whthDiawArr[0].replace(/[^0-9\.]/g, '') + '.' + whthDiawArr[1].replace(/[^0-9\.]/g, '');
      //   return;
      // }
      // this.withdrawalAmount = this.withdrawalAmount.replace(/[^0-9\.]/g, ''); // 去掉不是数字的部分
      // if(this.withdrawalAmount[0] === '.'){              // 如果字符串第一个位置是小数点 就加0(和第一个判断不一样)
      //   this.withdrawalAmount = '0' + this.withdrawalAmount;
      // }

      switch (true) {
        case this.withdrawalAmount === '.':
          this.withdrawalAmount = '0.';
          break;
        case isNaN(numF):
        case num > Number.MAX_SAFE_INTEGER:
          this.withdrawalAmount = '';
          break;
        case whthDiawArr.length > 2:
          this.withdrawalAmount = whthDiawArr[0].replace(/[^0-9\.]/g, '') + '.' + whthDiawArr[1].replace(/[^0-9\.]/g, '');
          break;
        default:
          this.withdrawalAmount = this.withdrawalAmount.replace(/[^0-9\.]/g, ''); // 去掉不是数字的部分
          break;
      }
      if (this.withdrawalAmount[0] === '.') {              // 如果字符串第一个位置是小数点 就加0(和第一个判断不一样)
        this.withdrawalAmount = '0' + this.withdrawalAmount;
      };
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

          this.handlingFee = res.data.attributes.cash_tax_ratio + '%';
          this.handlingFee1 = (res.data.attributes.cash_tax_ratio / 100)
        }
      })
    },


    sendVerificationCode() {
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
          this.$toast.fail(res.errors[0].code);
          // throw new Error(res.error)
        } else {
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
        if (res.errors) {
          this.$toast.fail(res.errors[0].code);
          // throw new Error(res.error)
        } else {
          this.mobileConfirmed = res.readdata._data.mobileConfirmed;
          if (this.mobileConfirmed == true) {
            this.$toast("提现申请已提交，请等待审核");
            // this.$router.push({path:'/modify-data'});
          }
        }
      })

      var phone = this.phone
      if (!phone) {
        this.$toast('请先绑定手机号')
        return
      }
      if (!this.wechatNickname) {
        this.$toast('请绑定微信')
        return
      }
      this.appFetch({
        url: 'cash',
        method: "post",
        data: {
          cash_apply_amount: this.withdrawalAmount
        }
      }).then((res) => {
        if (res.errors) {
          this.$toast.fail(res.errors[0].code);
          // throw new Error(res.error)
        } else {
          // this.actualCashWithdrawal = res.data.attributes.cash_actual_amount; //实际提现金额
          this.canWithdraw = res.data.attributes.cash_apply_amount; //用户申请提现的金额
          this.handlingFee = res.data.attributes.cash_charge;//提现手续费
          // this.handlingFee1 = (this.handlingFee/100)
        }
      })

    },

  }
}
