/**
 * pc 端首页控制器
 */
import browserDb from '../../../../../helpers/webDbHelper';
export default {
  	data: function () {
		return {
			searchVal: '',
			userParams: {
				'filter[username]': this.searchVal,
				// 'filter[id]': browserDb.getLItem('tokenId'),
				'filter[group_id]': [],
				'filter[bind]': 1,
				'page[limit]': 5,
				'page[number]': this.pageNumber,
				'sort': '-createdAt',
				'include': 'groups'
			},
			themeParamd: {
				'filter[q]': this.searchVal,
				'filter[isDeleted]': 'no',
				'page[limit]': 5,
				'page[number]': this.pageNumber,

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
			pageNumber:1,
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
				this.userParams['filter[username]'] = this.searchVal;
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
					// data:{
					// 	currentPageNum:this.userParams
					// }
					data: this.userParams
				}).then(data=>{
					this.searchUserList = this.searchUserList.concat(data.readdata);
					this.userLoadMoreStatus = data.readdata.length < this.userParams['page[limit]'];
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
			this.pageNumber++
			// this.userParams['page[number]']++;
			console.log(this.userParams['page[number]']++)
			// this.userParams['page[limit]'] = 10;
			this.userLoadMorePageChange = true;
			this.handleSearchUser();
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
					data:{
						include: ['user', 'firstPost'],
						'filter[q]': this.searchVal,
						'filter[isDeleted]': 'no',
						'page[limit]': 5,
						'page[number]': this.pageNumber,
					}
					// data: this.themeParamd
				}).then(data=>{
					this.searchThemeList = this.searchThemeList.concat(data.readdata);
					this.themeLoadMoreStatus = data.readdata.length < this.themeParamd['page[limit]'];
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
			this.pageNumber++
			// this.themeParamd['page[number]']++;
			// this.themeParamd['page[limit]'] = 10;
			this.themeLoadMorePageChange = true;
			this.handleSearchTheme();
		},

	},

	mounted: function () {

	},
	beforeRouteLeave(to, from, next) {
		next()
  }
}
