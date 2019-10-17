// 网站基础模块 mutation方法

import Vue from "vue";
import {
	SET_LOADING,SET_NUM
} from "../mutationTypes";

export default {
	/**
	 * 设置加载状态
	 * @param  {[type]} state   [description]
	 * @param  {[type]} payload [description]
	 * @return {[type]}         [description]
	 */
	[SET_LOADING] (state, payload) {
		var nowLoadingNum = state.loading + payload;

		nowLoadingNum = nowLoadingNum >= 0 ? nowLoadingNum : 0;
		state.index.loading = nowLoadingNum;
  },


  [SET_NUM](state,payload){
    state.num++;
    console.log(payload);
  }

}

