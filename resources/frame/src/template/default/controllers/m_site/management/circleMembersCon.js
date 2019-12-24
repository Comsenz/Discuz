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
	  offset: 100,
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
      	if(initStatus){
      		this.searchUserList = [];
      	}
      	try{
			//   const params = this.userParams['filter[username]']
			  await this.appFetch({
				  url:'users',
				  method:'get',
				  data:{
					'filter[username]': this.searchVal,
					'page[number]': this.pageIndex,
          			'page[limit]': 15
				  }
			  }).then(data=>{
				this.searchUserList = this.searchUserList.concat(data.readdata);
				this.pageIndex++;
			  })
      		// await this.apiStore.find('users', this.userParams).then(data=>{
      		// 	this.searchUserList = this.searchUserList.concat(data);
      		// 	// console.log(data,'user list data')
			//   })
			  .catch(err=>{
      		})
      	} finally {
      		this.userLoadMorePageChange = false;
      	}
      },

      handleLoadMoreUser(){
      	this.userLoadMorePageChange = true;
      	this.handleSearchUser();
	  },
	  onLoad(){    //上拉加载
		// const params = this.userParams['filter[username]']
        this.appFetch({
          url:'users',
          method:'get',
          data:{
			'filter[username]': this.searchVal,
			'page[number]': this.pageIndex,
            'page[limit]': 15
          }
        }).then(res=>{
			this.loading = false;
			if(res.readdata.length > 0){
			  this.searchUserList = this.searchUserList.concat(res.readdata);
			  this.pageIndex++;
			  this.finished = false; //数据全部加载完成
          }else{
            this.finished = true
          }

        console.log(this.finished,'00000000000000000000')

        })
      },
      onRefresh(){
        setTimeout(()=>{
			this.pageIndex = 1
			this.handleSearchUser();
            this.$toast('刷新成功');
            this.isLoading = false;
            this.finished = false;
          
        },200)
      }
	},

	mounted () {

	},
	beforeRouteLeave (to, from, next) {
    next();
	}
}
