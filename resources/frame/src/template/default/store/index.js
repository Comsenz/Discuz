// 表单模块 vuex
import getters from "./getters";
import mutations from "./mutations";
import actions from "./actions";

export default{
	namespaced: true,
	state: function() {
		return {
      status:0,
      openid:''
		};
	},
	getters,
	mutations,
	actions
};









