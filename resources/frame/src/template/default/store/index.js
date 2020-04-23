// 表单模块 vuex
import getters from "./getters";
import mutations from "./mutations";
import actions from "./actions";

export default {
  namespaced: true,
  state: function() {
    return {
      status: 0,
      openid: "",
      forum: null, //全局forum对象，保存/api/forum的返回值
      forumState: 'FORUM_INIT'
    };
  },
  getters,
  mutations,
  actions
};
