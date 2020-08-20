/**
 * 首页控制器
 */
import Card from '../../../view/site/common/card/card';
import axios from 'axios'

export default {
  data:function () {
    return {
      siteInfo:{},   //系统信息
      newVersion: false,  // 新版本是否显示
      versionNumber: '',
      oldVersion: '',
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
        this.oldVersion = res.data.attributes.version;
        this.compareSize();
      }
    });
  },
  methods: {
    compareSize() {
      this.versionNumber = dzq_latest_ver();
      const versNum = this.versionNumber.replace(/[^\d]/g, '');
      const versNum2  = this.oldVersion.replace(/[^\d]/g, '');
      if(versNum > versNum2) {
        this.newVersion = true;
      } else {
        this.newVersion = false;
      }
    }
  },
  components:{
    Card
  }
}
