/*
* 验证身份管理器
* */

import verifyPayPwdHeader from '../../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';

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
    },
    onDelete() {
      this.value = this.value.slice(0, this.value.length - 1);
    }
  },
  components: {
    verifyPayPwdHeader
  },
}
