import Vue from "vue";
import moment from "moment";
Vue.filter('timeAgo',function(value){
  return moment().fromNow();  // ~~~时间之前
})

Vue.filter('filterHtml',{
    read:function(val){//val就是以获取的msg的值
        return val.replace(/<[^>]*>/g);//去除文字的<...></...>标签
    },
    write:function(){
        return val;
    }
});
