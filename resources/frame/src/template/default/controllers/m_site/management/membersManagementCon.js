/**
 * 移动端圈子管理页控制器
 */
import browserDb from '../../../../../helpers/webDbHelper';
export default {
	data: function() {
		return {
			result: ['选中且禁用','复选框 A'],
			userList: [],
			choiceShow: false,
			choList: [
				'设为合伙人',
				'设为嘉宾',
				'设为成员',
				'禁用',
				'解除禁用'
			],
			searchName: '',
			userParams: {
				'filter[name]': '',
				'filter[id]': '写获取到的用户id',
				'filter[group_id]': [],
				'filter[bind]': 1,
				'page[limit]': 10,
				'page[number]': 1,
				'sort': '-createdAt',
				'include': 'groups'
			},
			userLoadMoreStatus: true,
			choiceRes: '选择操作'
		}
	},
	 //用于数据初始化
    created: function(){
		this.handleSearch();
	},
	methods: {
	    //选中复选框
	    toggle(index) {
	      this.$refs.checkboxes[index].toggle();
	    },
	    //操作列表显示
	    showChoice() {
	    	this.choiceShow = !this.choiceShow;
	    },
	    //操作列表隐藏
	    setSelectVal:function(val){
            this.choiceShow = false;
            this.choiceRes=val;
		},

		// 根据搜索进行请求
		async getSearchValUserList(initStatus){
			if(initStatus){
				this.userList = [];
			}
			try{
				const currentPageNum = this.userParams['page[number]'];
				await this.apiStore.find('searchUser', this.userParams).then(data=>{
					this.userList = this.userList.concat(data);
					this.userLoadMoreStatus = data.length > this.userParams['page[limit]'];
				}).catch(err=>{
					if(this.userLoadMorePageChange && this.userParams['page[number]'] > 1){
						this.userParams['page[number]'] = currentPageNum - 1;
					}
				})
			} finally {
				this.userLoadMorePageChange = false;
			}
		},

		// 通过搜索获取用户列表
		handleSearch(e){
			if(e){
				var value = e.target.value;
				this.searchName = value;
			} else {
				this.searchName = '';
			}

			this.userParams = {
				'filter[name]': this.searchName,
				'filter[id]': 1,
				'filter[group_id]': [],
				'filter[bind]': 1,
				'page[limit]': 10,
				'page[number]': 1,
				'sort': '-createdAt',
				'include': 'groups'
			}
			this.getSearchValUserList(true);
		}
	},

	mounted: function() {
		
	},
	beforeRouteLeave (to, from, next) {
	   
	}
}
