// 网站基础模块 mutation方法

import Vue from "vue";
import {
  SET_STATUS,
  SET_OPENID,
  SET_FORUM,
  SET_FORUM_STATE,
  SET_FORUM_PROMISE,
  SET_USER,
  SET_USER_STATE,
  SET_USER_PROMISE
} from "./mutationTypes";

export default {
  /*
   * 测试用例
   * */
  [SET_STATUS](state, payload) {
    state.status += 1;
  },
  [SET_OPENID](state, payload) {
    state.openid = payload;
  },
  [SET_FORUM](state, payload) {
    state.forum = payload;
  },
  [SET_FORUM_STATE](state, payload) {
    state.forumState = payload;
  },
  [SET_FORUM_PROMISE](state, payload) {
    state.forumPromise = payload;
  },
  [SET_USER](state, payload) {
    state.user = payload;
  },
  [SET_USER_STATE](state, payload) {
    state.userState = payload;
  },
  [SET_USER_PROMISE](state, payload) {
    state.userPromise = payload;
  },
};
