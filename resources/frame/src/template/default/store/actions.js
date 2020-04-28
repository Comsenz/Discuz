// 表单模块 actions
import Vue from "vue";
import {
  SET_FORUM,
  SET_FORUM_STATE,
  SET_FORUM_PROMISE,
  SET_USER,
  SET_USER_STATE
} from "./mutationTypes";
import browserDb from "../../../helpers/webDbHelper";

export default {
  loadForum({ commit, state }) {
    return new Promise((resolve, reject) => {
      if (state.forumState == "FORUM_LOADED") {
        resolve(state.forum);
        return;
      }
      if (state.forumState == "FORUM_LOADING") {
        state.forumPromise.then(res => {
          resolve(res);
        });
      }
      commit(SET_FORUM_STATE, "FORUM_LOADING");
      let promise = Vue.prototype
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
            commit(SET_FORUM_STATE, "FORUM_ERROR");
            if (res.rawData[0].code == "not_install") {
              window.location.href = res.rawData[0].detail.installUrl;
              return;
            }
          } else {
            browserDb.setLItem("siteInfo", res.readdata);
            let siteInfoStat = res.readdata._data.set_site.site_stat;
            app.bus.$emit("stat", siteInfoStat);
            commit(SET_FORUM_STATE, "FORUM_LOADED");
          }
          resolve(res);
        });
      commit(SET_FORUM_PROMISE, promise);
    });
  },
  invalidateForum({ commit }) {
    commit(SET_FORUM_STATE, "FORUM_INIT");
  },
  loadUser({ commit, state }) {
    return new Promise((resolve, reject) => {
      if (state.userState == "USER_LOADED") {
        resolve(state.user);
        return;
      }
      let userId = browserDb.getLItem("tokenId");
      if (!userId) {
        reject();
        return;
      }
      commit(SET_USER_STATE, "USER_LOADING");
      Vue.prototype
        .appFetch({
          url: "users",
          method: "get",
          splice: "/" + userId,
          data: {
            include: "groups,wechat"
          }
        })
        .then(res => {
          commit(SET_USER, res);
          commit(SET_USER_STATE, "USER_LOADED");
          resolve(res);
        });
    });
  },
  invalidateUser({ commit }){
    commit(SET_USER_STATE, "USER_INIT");
  }
};
