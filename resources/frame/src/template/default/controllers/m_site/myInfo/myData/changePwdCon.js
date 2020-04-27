/**
 * 修改密码
 */


import ChangePWDHeader from '../../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import browserDb from '../../../../../../helpers/webDbHelper';


export default {
  data: function () {
    return {
      pwd: '', //旧密码
      newpwd: "", //新密码
      confirmpwd: '', //确认密码
      loading: '', //loading状态
      btnLoading:false, //提交按钮loading状态
    }
  },

  components: {
    ChangePWDHeader
  },
  mounted() {
    // this.ChangePwd()
  },
  methods: {
    subm() {
      if (this.pwd === '') {
        this.$toast("旧密码不能为空");
        return;
      }

      if (this.newpwd === '') {
        this.$toast("新密码不能为空");
        return;
      }
      if (this.confirmpwd === '') {
        this.$toast("确认密码不能为空");
        return;
      }

      if (this.newpwd === this.pwd) {
        this.$toast("新旧密码不能相同");
        return;
      }

      if (this.newpwd !== this.confirmpwd) {
        this.$toast("新密码与确认密码不一致");
        return;
      }

      this.loading = true;

      this.btnLoading = true;

      const userId = browserDb.getLItem('tokenId');
      this.appFetch({
        url: 'users',
        method: 'patch',
        splice: '/' + userId,
        data: {
          "data": {
            "attributes": {
              "password": this.pwd,
              "newPassword": this.newpwd,
              "password_confirmation": this.confirmpwd,
              // "mobile": "186xxxx0384",
              // "status": 1
            }
          }
        }
      }).then((res) => {
        this.btnLoading = false;
        if (res.errors) {
          if (res.errors[0].detail) {
            this.$toast.fail(res.errors[0].code + '\n' + res.errors[0].detail[0])
          } else {
            this.$toast.fail(res.errors[0].code);
          }
        } else {
          this.$toast("密码修改成功");
          this.$router.push({ path: '/modify-data' });
        }

      })
    },
  }

}
