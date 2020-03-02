
/*
* 设置支付密码控制器
* */

import setUpPayPwdHeader from '../../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';

export default {
  data:function () {
    return {
      value: '',
      showKeyboard: true
    }
  },
  methods:{
    onInput(key) {
      this.value = (this.value + key).slice(0, 6);
      if (this.value.length === 6){
        this.$router.replace({name:'confirm-pay-pwd',params:{value:this.value}})
      }
    },
    onDelete() {
      this.value = this.value.slice(0, this.value.length - 1);
    }
  },
  components:{
    setUpPayPwdHeader
  }
}
