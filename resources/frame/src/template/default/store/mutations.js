// 网站基础模块 mutation方法

import Vue from "vue";
import {
	SET_STATUS,SET_OPENID
} from "./mutationTypes";

export default {
  /*
  * 测试用例
  * */
  [SET_STATUS](state,payload){
    state.status += 1;
  },
  [SET_OPENID](state,payload){
    state.openid = payload;
  }

}

