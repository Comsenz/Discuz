/**
 * pc 端首页控制器
 */
import browserDb from '../../../../../helpers/webDbHelper';
export default {
  	data: function () {
		return {
			searchVal: '',
			userParams: {
				'filter[name]': '',
				// 'filter[id]': browserDb.getLItem('tokenId'),
				'filter[group_id]': [],
				'filter[bind]': 1,
				'page[limit]': 5,
				'page[number]': 1,
				'sort': '-createdAt',
				'include': 'groups'
			},
			themeParamd: {
				'filter[q]': '',
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
		}
  	},

	methods: {
		onSearch(val) {
			clearTimeout(this.timerSearch);
			this.searchVal = val;
			console.log(val,'valvalvalval')
			if(this.searchVal === ''){
				this.searchUserList = [];
				this.searchThemeList = [];
				return;
			}
			this.timerSearch = setTimeout(()=>{

				this.firstComeIn = false;

				this.userParams['filter[name]'] = this.searchVal

				this.handleSearchUser(true);

				this.themeParamd['filter[q]'] = this.searchVal;
				this.handleSearchTheme(true);

			},200)
		},
		onCancel() {
			console.log('99999999999999')
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
			this.userParams['page[number]']++;
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
					// data:{
					// 	currentPageNum :this.themeParamd
					// }
					data: this.themeParamd
				}).then(data=>{
					console.log(data,'datadatadata')
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
			this.themeParamd['page[number]']++;
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
