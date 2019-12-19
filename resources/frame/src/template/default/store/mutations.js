// 网站基础模块 mutation方法

import Vue from "vue";
import {
	SET_STATUS
} from "./mutationTypes";

export default {
  /*
  * 测试用例
  * */
  [SET_STATUS](state,payload){
    console.log(state);
    state.status += 1;
  }
}

