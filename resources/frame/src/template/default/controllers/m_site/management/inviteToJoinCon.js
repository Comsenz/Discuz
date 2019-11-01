/**
 * 移动端圈子管理页控制器
 */

export default {
	data: function() {
		return {
			inviteList: [
				{
					number: '3',
					role: '合伙人',
					hrefStatus: '已使用',
					operation: ''
				},
				{
					number: '2',
					role: '合伙人',
					hrefStatus: '已使用',
					operation: ''
				}	
			],
			choiceShow: false,
			choList: [
				'设为合伙人',
				'设为嘉宾',
				'设为成员',
				'禁用',
				'解除禁用'
			],
			choiceRes: '选择操作'
		}
	},
	 //用于数据初始化
    created: function(){
		// console.log(this.headOneShow)
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
	},

	mounted: function() {
		
	},
	beforeRouteLeave (to, from, next) {
	   
	}
}
