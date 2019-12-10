/**
 * 移动端站点管理页控制器
 */
import myInviteJoinHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
export default {
	data: function() {
		return {
			inviteList: [],
			choiceShow: false,
			checkOperaStatus: false,
			choList: [],
			getGroupNameById: {},
			choiceRes: {attributes:{name: '选择操作'}},
		}
	},
	components:{
	  myInviteJoinHeader
	},
	 //用于数据初始化
    created: async function(){
		// console.log(this.headOneShow)
		await this.getOperaType();
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
			this.checkOperaStatus = true;
            this.choiceRes=val;
		},
		
		// 获取操作类型
		async getOperaType(){
			try{
				const response = await this.appFetch({
					url:'groups',
					method: 'get'
				})
				this.choList = response.data;
				for(let val of this.choList){
					this.getGroupNameById[val.id] = val.attributes.name;
				}
			} catch(err){
				console.log(err,'membersManagementCon.js getOperaType');
				// 这个地方需要写一个提示语  如果这个接口请求步成功的话  当前页面的操作就进行不了
				this.$toast("邀请码类型获取失败，请刷新重试");
			}
		},

		// 获取邀请码列表
		async getInviteList(){
			try{
				// const response = await this.apiStore.find('invite', this.userParams)
				const response = await this.appFetch({
					method: 'get',
					url: 'invite',
					data: this.userParams
				})
				this.inviteList = response.readdata;
				console.log(response,'invite response')
			} catch(err){
				console.error(err, '邀请码列表获取失败');
				this.$toast("邀请列表获取失败");
			}
		},

		// 生成邀请码点击事件
		async checkSubmit(){
			if(!this.checkOperaStatus){
				// 提示用户选择邀请码类型
				return;
			}
			try{
				await this.appFetch({
					url: 'invite',
					method: 'post',
					data: {
						data: {
							type: "invite",
							attributes: {
								group_id: parseInt(this.choiceRes.id)
							}
						}
					}
				})
				this.getInviteList();
			} catch(err){
				console.error(err,'checkSubmit')
			}
		},

		copyToClipBoard(inviteItem) {
			if(inviteItem._data.status === 0){
				return;
			}
			var textarea = document.createElement('textarea');
			textarea.style.position = 'absolute';
			textarea.style.opacity = '0';
			textarea.style.height = '0';
			textarea.textContent = inviteItem._data.code;
		  
			document.body.appendChild(textarea);
			textarea.select();
		  
			try {
			  	return document.execCommand('copy');
			} finally {
				document.body.removeChild(textarea);
			}
		},

		// 置为无效的点击事件
		async resetDelete(inviteItem){
			if(inviteItem._data.status === 0){
				return;
			}
			const id = inviteItem._data.id;
			try{
				await this.appFetch({
					url: 'invite',
					method: 'delete',
					splice: `/${id}`
				})
				this.checkSubmit();
			} catch(err){
				this.$toast("邀请码操作失败！");
			}
			
		}
	},

	mounted: function() {
		
	},
	beforeRouteLeave (to, from, next) {
	   
	}
}
