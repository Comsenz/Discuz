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
			themeLoadMoreStatus: true
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

			// this.themeParamd = {
			// 	'filter[q]': this.searchVal,
			// 	'page[limit]': 2,
			// 	'page[number]': 1,
			// }
			this.themeParamd = {
				'filter[q]': '',
				'page[limit]': 2,
				'page[number]': 1,
			}
			this.handleSearchTheme(true);
		},
		onCancel() {

		},

		handleSearchUser(initStatus = false){
			if(initStatus){
				this.searchUserList = [];
			}
			const currentPageNum = this.userParams['page[number]'];
			this.apiStore.find('searchUser', this.userParams).then(data=>{
				this.searchUserList = this.searchUserList.concat(data);
				this.userLoadMoreStatus = data.length > this.userParams['page[limit]'];
				console.log(data,'user list data')
			}).catch(err=>{
				this.userParams['page[number]'] = currentPageNum - 1;
			})
		},

		handleLoadMoreUser(){
			this.userParams['page[number]']++;
			this.handleSearchUser();
		},

		handleSearchTheme(initStatus = false){
			if(initStatus){
				this.searchThemeList = [];
			}
			const currentPageNum = this.themeParamd['page[number]']; 
			this.apiStore.find('searchThreads', this.themeParamd).then(data=>{
				this.searchThemeList = this.searchThemeList.concat(data);
				this.themeLoadMoreStatus = data.length > this.themeParamd['page[limit]'];
				console.log(data,'theme list data')
			}).catch(err=>{
				this.themeParamd['page[number]'] = currentPageNum - 1;
			})
		},

		handleLoadMoreTheme(){
			this.themeParamd['page[number]']++;
			this.handleSearchTheme();
		}

	},

	mounted: function () {

	},
	beforeRouteLeave(to, from, next) {

  }
}
