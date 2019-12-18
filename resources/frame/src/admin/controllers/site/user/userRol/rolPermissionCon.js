/*
* 角色权限编辑
* */

import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      checked:false
    }
  },
  methods:{

    /*
    * 接口请求
    * */
    getGroupResource(){
      this.appFetch({
        url:"groups",
        method:'get',
        splice: '/' + this.$route.query.id,
        data:{}
      }).then(res=>{
        console.log(res);
      }).catch(err=>{
        console.log(err);
      })
    }
  },
  created(){
    this.getGroupResource();
  },
  components:{
    Card,
    CardRow
  }
}
