/*
* 验证身份管理器
* */

import verifyPayPwdHeader from '../../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import webDb from '../../../../../../helpers/webDbHelper';

export default {
  data:function () {
    return {
      value: '',
      showKeyboard: true,
      pwdShow:true,
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
  created(){
    this.pwdShow = webDb.getLItem('siteInfo')._data.qcloud.qcloud_sms;
  },
  components: {
    verifyPayPwdHeader
  },
}
