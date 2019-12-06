import actions from './actions';
import getters from './getters';
import mutations from './mutations'

export default {
  namespaced:true,
  state(){
    return {
      searchData:{
        categoryId:'',           //主题分类ID
        pageSelect:'10',         //每页显示数
        themeAuthor:'',          //主题作者
        themeKeyWords:'',        //主题关键词
        dataValue:'',            //发表时间范围
        viewedTimesMin:'',       //被浏览次数最小
        viewedTimesMax:'',       //被浏览次数最大
        numberOfRepliesMin:'',   //被回复数最小
        numberOfRepliesMax:'',   //被回复数最大
        essentialTheme:'',       //精华主题类型
        topType:''               //置顶主题类型
      }
    };
  },
  getters,
  mutations,
  actions
}
