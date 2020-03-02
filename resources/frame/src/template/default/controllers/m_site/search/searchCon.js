/**
 * pc 端首页控制器
 */
import browserDb from '../../../../../helpers/webDbHelper';
export default {
  	data: function () {
		return {
			searchVal: '',
			userParams: {
				'filter[username]': '',
				'filter[group_id]': [],
				'page[limit]': 5,
				'page[number]': 1,
				'sort': 'createdAt',
				'include': 'groups'
			},
			themeParamd: {
				include: ['user', 'firstPost'],
				'filter[q]': '',
				'filter[isDeleted]': 'no',
				'page[limit]': 5,
				'page[number]': 1,
			},
			firstComeIn: true, // 是否首次进入页面
			searchUserList: [],
			searchThemeList: [],
			userLoadMoreStatus: false,
			themeLoadMoreStatus: false, // 是否还有更多主题
			userLoadMorePageChange: false,
			themeLoadMorePageChange: false, // 主题加载更多事件触发
			userLoading: false,
			themeLoading: false,
			timerSearch: null, // 延迟器
			searchMaxSum: 3,
		}
  	},

	methods: {
		onSearch(val) {
			clearTimeout(this.timerSearch);
			this.searchVal = val;
			if(this.searchVal === ''){
				this.searchUserList = [];
				this.searchThemeList = [];
				return;
			}
			this.timerSearch = setTimeout(()=>{

				this.firstComeIn = false;

				// 用户搜索
				this.userParams['filter[username]'] = '*' + this.searchVal + '*';
				this.userParams['page[number]'] = 1;

				this.handleSearchUser(true);

				// 主题搜索
				this.themeParamd['filter[q]'] = this.searchVal;
				this.themeParamd['page[number]'] = 1;

				this.handleSearchTheme(true);

			},200)
		},
		onCancel() {
			this.$router.push({ path:'/'});
		},

		async handleSearchUser(initStatus = false){
			if(initStatus){
				this.searchUserList = [];
			}
			if(this.userLoading){
				return;
			}
			this.userLoading = true;
			try{
				const currentPageNum = this.userParams['page[number]'];
				await this.appFetch({
					url:'users',
					methods:'get',
					data: this.userParams
				}).then(data=>{
					this.userLoadMoreStatus = data.readdata.length > this.searchMaxSum;
					this.searchUserList = data.readdata.splice(0,3);
				}).catch(err=>{
					if(this.userLoadMorePageChange && this.userParams['page[number]'] > 1){
						this.userParams['page[number]'] = currentPageNum - 1;
					}
				})
			} finally {
				this.userLoadMorePageChange = false;
				this.userLoading = false;
				// this.userParams['page[limit]'] = 2;
			}
		},

		handleLoadMoreUser(){
			// // this.userParams['page[number]']++;
			// // this.userParams['page[limit]'] = 10;
			// this.userLoadMorePageChange = true;
			// this.handleSearchUser();
			this.$router.push({path: '/circle-members', query: {searchWord: this.searchVal}})
		},

		async handleSearchTheme(initStatus = false){
			if(initStatus){
				this.searchThemeList = [];
			}
			if(this.themeLoading){
				return;
			}
			this.themeLoading = true;
			try {
				const currentPageNum = this.themeParamd['page[number]'];
				await this.appFetch({
					url:'searchThreads',
					method:'get',
					data: this.themeParamd
				}).then(data=>{
					console.log(data,'主题')
					console.log(data.readdata[0]._data,'0000')
					this.themeLoadMoreStatus = data.readdata.length > this.searchMaxSum;
					this.searchThemeList = data.readdata.splice(0,3);
				}).catch(err=>{
					if(this.themeLoadMorePageChange && this.themeParamd['page[number]'] > 1){
						this.themeParamd['page[number]'] = currentPageNum - 1;
					}
				})
			} finally {
				this.themeLoadMorePageChange = false;
				this.themeLoading = false;
				// this.themeParamd['page[limit]'] = 2;
			}
		},

		handleLoadMoreTheme(){
			// // this.themeParamd['page[number]']++;
			// // this.themeParamd['page[limit]'] = 10;
			// this.themeLoadMorePageChange = true;
			// this.handleSearchTheme();
			this.$router.push({path: '/theme-search', query: {searchWord: this.searchVal}})
		},

		//点击用户名称，跳转到用户主页
		jumpPerDet:function(id){
			  this.$router.push({ path:'/home-page'+'/'+id});
		  },
		//点击主题内容，跳转到详情页
		jumpDetails:function(id){
			this.$router.push({ path:'/details'+'/'+id});
		},

	},

	mounted: function () {

	},
	beforeRouteLeave(to, from, next) {
		next()
  }
}
