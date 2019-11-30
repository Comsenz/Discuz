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
      this.siteInfo = res.data.attributes;
    });
  },

  components:{
    Card
  }


}
