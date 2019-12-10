/**
 * 移动端站点管理页控制器
 */
export default {
	data: function () {
		return {
			result: [],
			checkList: [],
			userList: [],
			choiceShow: false,
			checkOperaStatus: false,
			choList: [],
			getGroupNameById: {},
			searchName: '',
			userParams: {
				'filter[name]': '',
				'page[limit]': 15,
				'page[number]': 1,
			},
			userLoadMoreStatus: false,
			userLoadMorePageChange: false,
			choiceRes: { attributes: { name: '选择操作' } },
		}
	},
	//用于数据初始化
	created: async function () {
		await this.getOperaType();
		this.handleSearch();
	},
	methods: {
		//选中复选框
		toggle(id) {
			var listLen = this.userList.length;
			if (listLen === 0) return;
			var checkList = [];
			for (let i = 0; i < listLen; i++) {
				let checkid = this.userList[i].id();
				if (checkid === id) {
					this.userList[i].checkStatus = !this.userList[i].checkStatus;
				}
				if (this.userList[i].checkStatus) {
					checkList.push(this.userList[i].username());
				}
			}
			this.result = checkList;
		},
		//操作列表显示
		showChoice() {
			this.choiceShow = !this.choiceShow;
		},
		//操作列表隐藏
		setSelectVal: function (val) {
			this.choiceShow = false;
			this.checkOperaStatus = true;
			this.choiceRes = val;
		},

		// 通过搜索获取用户列表
		handleSearch(e) {
			if (e) {
				var value = e.target.value;
				this.searchName = value;
			} else {
				this.searchName = '';
			}

			this.userParams = {
				'filter[name]': this.searchName,
				'page[limit]': 15,
				'page[number]': 1,
			}
			this.getSearchValUserList(true);
		},

		// 根据搜索进行请求
		async getSearchValUserList(initStatus = false) {
			try {
				const response = await this.apiStore.find('users', this.userParams)
				if (initStatus) {
					this.userList = [];
				}
				this.userList = this.userList.concat(response).map((v, i) => {
					var obj = v;
					obj.checkStatus = false;
					return obj
				});
				console.log(this.userList, 'userList')
				this.userLoadMoreStatus = response.length >= this.userParams['page[limit]'];
			} catch (err) {
				console.error(err, 'membersManagementCon.js getSearchValUserList');
				const currentPageNum = this.userParams['page[number]'];
				if (this.userLoadMorePageChange && this.userParams['page[number]'] > 1) {
					this.userParams['page[number]'] = currentPageNum - 1;
				}
			} finally {
				this.userLoadMorePageChange = false;
			}
		},

		// 获取操作类型
		async getOperaType() {
			try {
				const response = await this.appFetch({
					url: 'groups',
					method: 'get'
				})
				this.choList = response.data;
				console.log(this.choList);
				for (let val of this.choList) {
					this.getGroupNameById[val.id] = val.attributes.name;
				}
				console.log(this.getGroupNameById)
			} catch (err) {
				console.log(err, 'membersManagementCon.js getOperaType');
				// 这个地方需要写一个提示语  如果这个接口请求步成功的话  当前页面的操作就进行不了
			} finally {

			}
		},

		// 提交按钮的点击事件
		// handleSubmit(){

		// 	if(!this.checkOperaStatus){
		// 		//提示为选择操作类型
		// 		console.log("未选择操作类型")
		// 		return;
		// 	}

		// 	if(this.result.length === 0){
		// 		// 提示未选择用户
		// 		console.log("未选择用户")
		// 		return;
		// 	}

		// 	let data = [];
		// 	const groupId = this.choiceRes.id;
		// 	for(let i = 0, len = this.userList.length; i < len; i++){
		// 		if(this.userList[i].checkStatus){
		// 			data.push({
		// 				"attributes": {
		// 					id: this.userList[i].id(),
		// 					groupId
		// 				}
		// 			})
		// 		}
		// 	}

		// 	this.appFetch({
		// 		url: 'users',
		// 		method: 'PATCH',
		// 		data: {
		// 			data
		// 		}
		// 	})
		// },


		async handleSubmit() {

			try {
				if (!this.checkOperaStatus) {
					//提示为选择操作类型
					this.$toast("未选择操作类型");
					return;
				}

				if (this.result.length === 0) {
					// 提示未选择用户
					this.$toast("未选择用户")
					return;
				}

				let data = [];
				const groupId = this.choiceRes.id;
				for (let i = 0, len = this.userList.length; i < len; i++) {
					if (this.userList[i].checkStatus) {
						data.push({
							"attributes": {
								id: this.userList[i].id(),
								groupId
							}
						})
					}
				}

				await this.appFetch({
					url: 'users',
					method: 'PATCH',
					data: {
						data
					}
				})
				this.getSearchValUserList(true);
			} catch (err) {
				console.error('handleSubmit error')
			}

		},

		handleLoadMoreUser() {
			this.userParams['page[number]']++;
			this.userLoadMorePageChange = true;
			this.getSearchValUserList();
		}
	},

	mounted: function () {

	},
	beforeRouteLeave(to, from, next) {

	}
}
