
export default {
  data: function () {
    return {
      closeReason: '',
    }
  },
  created() {
    this.getCloseReason()
  },
  methods: {
    getCloseReason() {
      this.appFetch({
        url: 'forum',
        method: 'get',
        data: {

        }
      }).then(res => {
        if (res.errors) {
          // this.closeReason = res.errors[0].detail;
          if (res.errors[0].detail == null || res.errors[0].detail == '') {
            // this.closeReason = '站点已关闭'
          } else {
            this.closeReason = res.errors[0].detail;
          }
        }
        // this.closeReason = res.readdata._data.siteCloseMsg
        // this.imgLogo = res.readdata._data.logo
      })
    },
    loginClick() {
      this.$router.push({ path: 'login-user' });  //跳到登录页
    }
  }
}

