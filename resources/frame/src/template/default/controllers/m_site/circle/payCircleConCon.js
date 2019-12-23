/**
 * pc 端首页控制器
 */

export default {
	data: function() {
		return {
      thread:false,
      sitePrice:''   //加入价格
		}
	},
  computed: {
      themeId: function(){
          return this.$route.params.themeId;
      }
  },
  created(){
    this.myThread();
    this.getInfo();
  },
	methods: {
    getInfo(){
      //请求站点信息，用于判断站点是否是付费站点
      this.appFetch({
        url: 'forum',
        method: 'get',
        data: {
          include: ['users'],
        }
      }).then((res) => {
        console.log(res);
        this.siteInfo = res.readdata;
        console.log(res.readdata._data.siteMode+'请求');
        if(res.readdata._data.siteAuthor){
          this.siteUsername = res.readdata._data.siteAuthor.username;
        } else {
          this.siteUsername = '暂无站长信息';
        }
        this.sitePrice = res.readdata._data.sitePrice
      });
    },
    myThread(){
     this.appFetch({
        url:'threads',
        method:'get',
        splice:'/'+this.themeId,
        data:{
          include: ['user', 'posts', 'posts.user', 'firstPost'],
        }
      }).then(res=>{
        console.log('123');
        console.log(res)
        this.thread = res.readdata;
        console.log(this.thread._data.createdAt);
        console.log('567');
      })
    },
		//跳转到登录页
		loginJump:function(){
			this.$router.push({ path:'/login-user'})
		},
		//跳转到注册页
		registerJump:function(){
			this.$router.push({ path:'/sign-up'})
		}

	},

	mounted: function() {
		// this.getVote();
		window.addEventListener('scroll', this.handleTabFix, true);
	},
	beforeRouteLeave (to, from, next) {
	   window.removeEventListener('scroll', this.handleTabFix, true)
	   next()
	}
}
