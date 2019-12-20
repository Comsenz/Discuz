/**
 * pc 端首页控制器
 */
export default {
	data: function() {
		return {
			// title: "纯净版框架22222",
			// description: "vue + webpack + vue-router + vuex + sass + prerender + axios ",
			// num: 0,
			// voteInfo: {}
			isfixNav: false,
			current:0,
      todos: [
        { text: '选项一111' },
        { text: '选项二' },
        { text: '选项三' },
        { text: '选项四' },
        { text: '选项五' },
        { text: '选项六' },
        { text: '选项七' },
        { text: '选项八' }
      ],
      siteInfo: false,
      siteUsername:'',  //站长
      joinedAt:'',    //加入时间
      sitePrice:'',   //加入价格
      username:''    //当前用户名
		}
	},
	created(){
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
        //把站点是否收费的值存储起来，以便于传到父页面
        // this.isPayVal = res.readdata._data.siteMode;
        // if(this.isPayVal != null && this.isPayVal != ''){
        //   this.isPayVal = res.readdata._data.siteMode;
        //   //判断站点信息是否付费，用户是否登录，用户是否已支付
        //   this.detailIf(this.isPayVal,false);
        // }
      });
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

	},
	beforeRouteLeave (to, from, next) {
    next()
	}
}
