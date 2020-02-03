// 表单模块 vuex
import getters from "./getters";
import mutations from "./mutations";
import actions from "./actions";

export default{
	namespaced: true,
	state: function() {
		return {
			loading: 0,		//loading状态，0为隐藏，非0显示
      num:0
		};
	},
	getters,
	mutations,
	actions
};









