/**
 * 移动端圈子管理页控制器
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
				// 'page[number]': 1,

			},
			searchUserList: [],
      userLoadMoreStatus: true,
      userLoadMorePageChange: false,
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
      		await this.apiStore.find('searchUser', this.userParams).then(data=>{
      			this.searchUserList = this.searchUserList.concat(data);
      			// console.log(data,'user list data')
      		}).catch(err=>{
      		})
      	} finally {
      		this.userLoadMorePageChange = false;
      	}
      },

      handleLoadMoreUser(){
      	this.userLoadMorePageChange = true;
      	this.handleSearchUser();
      }
	},

	mounted () {

	},
	beforeRouteLeave (to, from, next) {
    next();
	}
}
