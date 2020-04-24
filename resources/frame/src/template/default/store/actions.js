// 表单模块 actions
import Vue from "vue";
import { SET_FORUM, SET_FORUM_STATUS } from "./mutationTypes";
import browserDb from '../../../helpers/webDbHelper';

export default {
  loadForum({ commit, state }) {
    return new Promise((resolve, reject) => {
      if (
        state.forumState == "FORUM_LOADED" ||
        state.forumState == "FORUM_LOADING"
      ) {
        resolve(state.forum);
        return;
      }
      commit(SET_FORUM_STATUS, "FORUM_LOADING");
      Vue.prototype
        .appFetch({
          url: "forum",
          method: "get",
          data: {
            include: ["users"]
          }
        })
        .then(res => {
          commit(SET_FORUM, res);
          if (res.errors) {
            commit(SET_FORUM_STATUS, "FORUM_ERROR");
            if (res.rawData[0].code == "not_install") {
              window.location.href = res.rawData[0].detail.installUrl;
              return;
            }
          } else {
            browserDb.setLItem('siteInfo', res.readdata);
            commit(SET_FORUM_STATUS, "FORUM_LOADED");
          }
          resolve(res);
        });
    });
  }
};
