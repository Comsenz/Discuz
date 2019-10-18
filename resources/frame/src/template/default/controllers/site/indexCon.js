/**
 * pc 端首页控制器
 */

export default {
	data: function() {
		return {
			title: "纯净版框架",
			description: "vue + webpack + vue-router + vuex + sass + prerender + axios ",
			num: 0,
			voteInfo: {}
		}
	},
	
	methods: {
		/**
		 * num 加1
		 */
		addNum: function() {
			this.num++;
		},

		/**
		 * 获取投票数据
		 * @return {[type]} [description]
		 */
		getVote: function() {
			var _this = this;

			this.appFetch({
				url: "getVote",
				method: "get",
				data: {
					vid: 40889
				}
			}, function(res) {
				if(res.code == 0) {
					_this.voteInfo = res.data;
				} else {
					console.error("获取投票信息失败");
				}
			}, function(error) {
				console.error(error.msg);
			});
		},

		module1CallBack: function() {

		},

		module2CallBack: function() {
			
		},

		getModuleInfo: function() {
			let data = {module1: {}, module2: {}};

			this.appCommonH.apiCallBack(this, data);
		}		
	},

	mounted: function() {
		this.getVote();
	}
}