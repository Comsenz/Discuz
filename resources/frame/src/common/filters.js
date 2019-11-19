import Vue from "vue";
import moment from "moment";
Vue.filter('timeAgo',function(value){
  return moment().startOf('day').fromNow();  // ~~~时间之前
;
})
