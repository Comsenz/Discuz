/**
 * 首页控制器
 */

import Card from '../../../view/site/common/card/card';

export default {
  data:function () {
    return {
      siteInfo:{}   //系统信息
    }
  },

  created(){
    this.appFetch({
      url:"siteinfo",
      method:"get",
      data:{}
    }).then(res => {
      if (res.errors){
        this.$toast.fail(res.errors[0].code);
      }else {
        this.siteInfo = res.data.attributes;
      }
    });
  },

  components:{
    Card
  }


}
