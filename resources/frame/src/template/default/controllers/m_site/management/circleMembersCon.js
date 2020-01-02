/**
 * 移动端站点管理页控制器
 */

export default {
	data: function() {
		return {
      	serHide:true,
      	serShow:false,
		searchVal: '',
		userParams: {
		 'filter[username]': '',
		},
		themeParamd: {
		// 'filter[q]': '',
		// 'page[limit]': 2,
		'page[number]': 1,
		},
	  	searchUserList: [],
      	userLoadMoreStatus: true,
	  	userLoadMorePageChange: false,
	  	loading: false,  //是否处于加载状态
      	finished: false, //是否已加载完所有数据
      	isLoading: false, //是否处于下拉刷新状态
      	pageIndex: 1,//页码
      	offset: 100, //滚动条与底部距离小于 offset 时触发load事件
      	immediateCheck:false ,//是否在初始化时立即执行滚动位置检查
      	pageLimit:20,
		}
	},
	 //用于数据初始化
  created: function(){
    // this.loadUserList();
    this.onSearch();
	},
	methods: {
    //搜索框切换
    serToggle(){
      this.serHide = false;
      this.serShow = true;
      this.$refs.serInp.focus();
    },
	onSearch(val) {
	  this.searchVal = val;
	// console.log(val,'value')
	  this.userParams = {
	    'filter[username]': this.searchVal,
	    }
	  this.handleSearchUser(true);
		},
	    onCancel() {
	    },
      async handleSearchUser(initStatus = false){
		// if(initStatus){
		// 	this.searchUserList = [];
		// }
      	try{
			//   const params = this.userParams['filter[username]']
			  await this.appFetch({
				  url:'users',
				  method:'get',
				  data:{
					'filter[username]': this.searchVal,
					'page[number]': this.pageIndex,
            		'page[limit]': this.pageLimit
				  }
			  }).then(data=>{
				if (data.errors){
					this.$toast.fail(data.errors[0].code);
					throw new Error(data.error)
				  }else{
				if(initStatus){
					this.searchUserList = [];
				}
				this.loading = false;
				this.searchUserList = this.searchUserList.concat(data.readdata);
				this.finished = data.readdata.length < this.pageLimit;
				  }
			  })
			  .catch(err=>{
				if(this.loading && this.pageIndex !== 1){
					this.pageIndex --;
				  }
      		})
      	} finally {
			  this.userLoadMorePageChange = false;
			  this.loading = false;
      	}
      },

      handleLoadMoreUser(){
      	this.userLoadMorePageChange = true;
      	this.handleSearchUser();
	  },
	  onLoad(){    //上拉加载
		this.loading = true;
		this.pageIndex++;
		this.handleSearchUser()
      },
      onRefresh(){
		this.pageIndex = 1
        this.handleSearchUser(true).then(()=>{
          this.$toast('刷新成功');
          this.isLoading = false;
          this.finished = false;
        }).catch((err)=>{
          this.$toast('刷新失败');
          this.isLoading = false;
        })
      }
	},

	mounted () {

	},
	beforeRouteLeave (to, from, next) {
    next();
	}
}
