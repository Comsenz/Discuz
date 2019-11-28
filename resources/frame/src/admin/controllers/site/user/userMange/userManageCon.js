/*
* 用户管理
* */

import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      options: [{
        value: '选项1',
        label: '黄金糕'
      }, {
        value: '选项2',
        label: '双皮奶'
      }, {
        value: '选项3',
        label: '蚵仔煎'
      }, {
        value: '选项4',
        label: '龙须面'
      }, {
        value: '选项5',
        label: '北京烤鸭'
      }],
      value: '',
      checked:false,
      radio1:'2',
      radio2:'2'
    }
  },

  methods:{
    checkedStatus(str){
      setTimeout(()=>{
        if (str){
          let gd =  document.getElementsByClassName('index-main-con__main')[0];
          gd.scrollTo(0,gd.scrollHeight);
        }
      },300);
    },
    searchBtn(){
      this.$router.push({path:'/admin/user-search-list'})
    }
  },

  components:{
    Card,
    CardRow
  }
}
