/**
 * 手机端端首页控制器
 */

export default {
	data: function() {
		return {
			title: "纯净版框架 mobile",
			description: "vue + webpack + vue-router + vuex + sass + prerender + axios +  element ui ",
			num: 0
		}
	},
	
	methods: {
		/**
		 * num 加1
		 */
		addNum: function() {
			this.num++;
		}
	}
}