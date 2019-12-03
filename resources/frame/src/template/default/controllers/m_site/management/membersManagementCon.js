/**
 * 移动端圈子管理页控制器
 */
import browserDb from '../../../../../helpers/webDbHelper';
export default {
	data: function() {
		return {
			result: ['选中且禁用','复选框 A'],
			list: ['a','b','c'],
			choiceShow: false,
			choList: [
				'设为合伙人',
				'设为嘉宾',
				'设为成员',
				'禁用',
				'解除禁用'
			],
			choiceRes: '选择操作',
			flag:true,
		}
	},
	 //用于数据初始化
    created: function(){
		console.log(this.headOneShow);
		this.membersInformation() //成员信息
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
		membersInformation(){  //成员信息
			var userId = browserDb.getLItem('tokenId');
			var params = {};
			params.include = 'groups,wechat'
			this.apiStore.find('users',userId,params).then(res=>{
			//   this.payee= res.data.attributes.username;
			//   this.phone = res.data.attributes.mobile;
			this.list = res.data;
			if(list.length<0){
				this.flag = false
			}
			console.log(this.list)
			})

		}
	},

	mounted: function() {
		
	},
	beforeRouteLeave (to, from, next) {
	   
	}
}
