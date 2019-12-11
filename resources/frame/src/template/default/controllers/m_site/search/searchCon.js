/**
 * pc 端首页控制器
 */

export default {
  	data: function () {
		return {
			searchVal: '',
			userParams: {
				'filter[name]': '',
				'filter[id]': '写获取到的用户id',
				'filter[group_id]': [],
				'filter[bind]': 1,
				'page[limit]': 2,
				'page[number]': 1,
				'sort': '-createdAt',
				'include': 'groups'
			},
			themeParamd: {
				'filter[q]': '',
				'page[limit]': 2,
				'page[number]': 1,

			},
			searchUserList: [],
			searchThemeList: [],
			userLoadMoreStatus: true,
			themeLoadMoreStatus: true,
			userLoadMorePageChange: false,
			themeLoadMorePageChange: false
		}
  	},

	methods: {
		onSearch(val) {
			this.searchVal = val;
			if(this.searchVal === ''){
				this.searchUserList = [];
				this.searchThemeList = [];
				return;
			}
			console.log(val,'value')
			this.userParams = {
				'filter[name]': this.searchVal,
				'filter[id]': 1,
				'filter[group_id]': [],
				'filter[bind]': 1,
				'page[limit]': 2,
				'page[number]': 1,
				'sort': '-createdAt',
				'include': 'groups'
			}
			this.handleSearchUser(true);

			this.themeParamd = {
				'filter[q]': this.searchVal,
				'page[limit]': 2,
				'page[number]': 1,
			}
			this.handleSearchTheme(true);
		},
		onCancel() {

		},

		async handleSearchUser(initStatus = false){
			if(initStatus){
				this.searchUserList = [];
			}
			try{
				const currentPageNum = this.userParams['page[number]'];
				await this.appFetch({
					url:'users',
					methods:'get',
					data:{
						currentPageNum:this.userParams
					}
				}).then(data=>{
						this.searchUserList = this.searchUserList.concat(data.readdata);
					    this.userLoadMoreStatus = data.length > this.userParams['page[limit]'];
					console.log(data.readdata[0]._data.username,'user list data')
					console.log(data.readdata[0])
				}).catch(err=>{
					if(this.userLoadMorePageChange && this.userParams['page[number]'] > 1){
						this.userParams['page[number]'] = currentPageNum - 1;
					}
				})
			} finally {
				this.userLoadMorePageChange = false;
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
			try {
				const currentPageNum = this.themeParamd['page[number]']; 
				await this.appFetch({
					url:'searchThreads',
					method:'get',
					data:{
						currentPageNum :this.themeParamd
					}
				
				// await this.apiStore.find('searchThreads', this.themeParamd).then(data=>{
				// 	this.searchThemeList = this.searchThemeList.concat(data);
				// 	this.themeLoadMoreStatus = data.length > this.themeParamd['page[limit]'];
				// 	console.log(data,'theme list data')
				}).then(data=>{
					this.searchThemeList = this.searchThemeList.concat(data.readdata);
					this.themeLoadMoreStatus = data.readdata.length > this.themeParamd['page[limit]'];
					console.log(this.searchThemeList,'11111111111111111111111')
					// console.log(data.readdata.firstPost._data.content)
				}).catch(err=>{
					if(this.themeLoadMorePageChange && this.themeParamd['page[number]'] > 1){
						this.themeParamd['page[number]'] = currentPageNum - 1;
					}
				})
			} finally {
				this.themeLoadMorePageChange = false;
				// this.themeParamd['page[limit]'] = 2;
			}
		},

		handleLoadMoreTheme(){
			this.themeParamd['page[number]']++;
			// this.themeParamd['page[limit]'] = 10;
			this.themeLoadMorePageChange = true;
			this.handleSearchTheme();
		}

	},

	mounted: function () {

	},
	beforeRouteLeave(to, from, next) {

  }
}
