import Vue from 'vue';
import {
  SET_LOGIN_STATE
} from '../mutationTypes'

export default {
  /**
   * 登录状态
   */
  [SET_LOGIN_STATE](state,payload){
    state.loginState = !state.loginState;
  }
}
