import Vue from 'vue';

import {
  SET_SEARCH_CONDITION
} from '../mutationTypes'

export default {
  /**
   * 搜索条件
   * state -> index.js/state定义的数据
   * payload 表示调用这个方法传过来的值
   */
  [SET_SEARCH_CONDITION](state,payload){
    //传过来的值对state内数据进行更改
    state.searchData.categoryId = payload.categoryId;
    state.searchData.pageSelect = payload.pageSelect;
    state.searchData.themeAuthor = payload.themeAuthor;
    state.searchData.themeKeyWords = payload.themeKeyWords;
    state.searchData.dataValue = payload.dataValue;
    state.searchData.viewedTimesMin = payload.viewedTimesMin;
    state.searchData.viewedTimesMax = payload.viewedTimesMax;
    state.searchData.numberOfRepliesMin = payload.numberOfRepliesMin;
    state.searchData.numberOfRepliesMax = payload.numberOfRepliesMax;
    state.searchData.essentialTheme =payload.essentialTheme;
    state.searchData.topType = payload.topType;
  }
}
