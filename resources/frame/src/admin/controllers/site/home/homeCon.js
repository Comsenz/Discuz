/**
 * 首页控制器
 */

import Card from '../../../view/site/common/card/card';

export default {
  data:function () {
    return {
      siteInfo:{},   //系统信息
      newVersion: false  // 新版本是否显示
    }
  },

  created(){
    this.appFetch({
      url:"siteinfo",
      method:"get",
      data:{}
    }).then(res => {
      if (res.errors){
        this.$message.error(res.errors[0].code);
      }else {
        this.siteInfo = res.data.attributes;
      }
    });
  },

  components:{
    Card
  }


}
