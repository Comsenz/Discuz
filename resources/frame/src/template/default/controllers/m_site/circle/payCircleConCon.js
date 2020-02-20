/**
 * 付费站点分享页控制器
 */
import browserDb from '../../../../../helpers/webDbHelper';
export default {
	data: function() {
		return {
      thread:{},
	    sitePrice:'',   //加入价格
	    loading: false,  //是否处于加载状态
      finished: false, //是否已加载完所有数据
      isLoading: false, //是否处于下拉刷新状态
      pageIndex: 1,//页码
      pageLimit: 20,
      offset: 100, //滚动条与底部距离小于 offset 时触发load事件
      thread:false,
      themeCon:[],
      limitList:'',
      allowRegister: '',
      token:'',
      alreadyLogin: '',
      loginName: ''
      
		}
	},
  computed: {
    themeId: function(){
        return this.$route.params.themeId;
    },
    groupId: function(){
        return this.$route.params.groupId;
    }
  },
  created(){
    this.token = browserDb.getLItem('Authorization');
    this.loginName = browserDb.getLItem('foregroundUser');
    if(this.token){
      this.alreadyLogin = true;
    } else {
      this.alreadyLogin = false;
    }
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
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        } else {
          console.log(res,'123456');
          this.siteInfo = res.readdata;
          // console.log(res.readdata._data.siteMode+'请求');
          if(res.readdata._data.set_site.site_author){
            this.siteUsername = res.readdata._data.set_site.site_author.username;
          } else {
            this.siteUsername = '暂无站长信息';
          }
          this.sitePrice = res.readdata._data.set_site.site_price;
          this.allowRegister = res.readdata._data.set_reg.register_close;
        }
      });

      //请求权限列表数据
      this.appFetch({
        url: 'groups',
        method: 'get',
        data: {
          'filter[isDefault]': '1',
          include: ['permission'],
        }
      }).then((res) => {
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        }else{
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        }else{
        console.log('000000');
        console.log(res);
        this.limitList = res.readdata[0];
        }
      }
      });


    },

    myThread(initStatus = false){
     this.appFetch({
        url:'shareThreads',
        method:'get',
        splice:'/'+this.themeId,
        data:{
        }
      }).then(res=>{
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        }else{
		    if(initStatus){
		    this.thread=[]
		}
        console.log('123');
        console.log(res)
        this.thread = res.readdata;
        console.log(this.thread._data.createdAt);
        console.log('567');
  }
      })
    },
		//跳转到登录页
		loginJump:function(){
			this.$router.push({ path:'/login-user'})
		},
		//跳转到注册页
		registerJump:function(){
			this.$router.push({ path:'/sign-up'})
		},
		onRefresh(){    //下拉刷新
			this.pageIndex = 1;
			this.myThread(true).then(()=>{
			  this.$toast('刷新成功');
			  this.finished = false;
			  this.isLoading = false;
			}).catch((err)=>{
			  this.$toast('刷新失败');
			  this.isLoading = false;
			})
    },
    //退出登录
    signOut(){
      browserDb.removeLItem('tokenId');
      browserDb.removeLItem('Authorization');
      // this.$router.push({ path:'/login-user'});
      this.alreadyLogin = false;
    },
    //付费，获得成员权限
    payClick(){

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
