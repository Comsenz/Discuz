/**
 * 修改密码
 */


import ChangeUsernameHeader from '../../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import browserDb from '../../../../../../helpers/webDbHelper';


export default {
  data: function () {
    return {
      newusername: "",
      loading: '', //loading状态
      btnLoading:false, //提交按钮loading状态
    }
  },

  components: {
    ChangeUsernameHeader
  },
  mounted() {
  },
  methods: {
    subm() {
      if (this.newusername === '') {
        this.$toast("新用户名不能为空");
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
              "username": this.newusername,
            }
          }
        }
      }).then((res) => {
        this.btnLoading = false;
        if (res.errors) {
          if (res.errors[0].detail) {
            this.$toast(res.errors[0].detail[0]);
          } else {
            this.$toast.fail(res.errors[0].code);
          }
        } else {
          this.$toast("用户名修改成功");
          this.$router.push({ path: '../view/m_site/home/circleView' });
        }
      })
    },
  }

}
