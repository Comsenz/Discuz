/**
 * pc 端首页控制器
 */

export default {
	data: function() {
		return {
			isfixNav: false,
      loginBtnFix: true,
			siteInfo: false,
      roleId:'',
      roleResult:''
		}
	},
  //用于数据初始化
  created: function(){
    // var roleId = this.$route.query.groupId;
    var roleId = '10';
    this.roleId = roleId;
    console.log(roleId);
    // if(role == '1'){
    //   this.roleResult="管理员";
    // } else if(role == '7'){
    //   this.roleResult="游客";
    // } else if(role == '10'){
    //   this.roleResult="普通成员";
    // } else {
    //   this.roleResult="其他";
    // }
    this.loadSite();
  },
	methods: {
    loadSite(){
      //请求初始化站点信息数据
      this.appFetch({
        url: 'forum',
        method: 'get',
        data: {
          include: ['users'],
        }
      }).then((res) => {
        // console.log(res);
        this.siteInfo = res.readdata;
        // console.log(res.readdata._data.siteIntroduction);
      });

      //请求初始化角色信息数据
      this.appFetch({
        url: 'groups',
        method: 'get',
        splice:'/' + this.roleId,
        data: {
        }
      }).then((res) => {
        // console.log(res);
        this.roleResult = res.readdata._data.name;
      });
    },

    logBtnFix() {
      var scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;
        if(scrollTop > 10){
          this.loginBtnFix = false;
        } else {
          this.loginBtnFix = true;
        };
    },


		//跳转到登录页
		loginJump:function(){
			this.$router.push({ path:'login-user'})
		},
		//跳转到注册页
		registerJump:function(){
			this.$router.push({ path:'sign-up'})
		},
		/**
		 * 给导航添加点击状态
		 */
		addClass:function(index,event){
        this.current=index;

　　　　　　 //获取点击对象
       var el = event.currentTarget;
       // alert("当前对象的内容："+el.innerHTML);
    }

	},

	mounted: function() {
		// this.getVote();
		window.addEventListener('scroll', this.logBtnFix, true);
	},
	beforeRouteLeave (to, from, next) {
	   window.removeEventListener('scroll', this.logBtnFix, true)
	   next()
	}
}
