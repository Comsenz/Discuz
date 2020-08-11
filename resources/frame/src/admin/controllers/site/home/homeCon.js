/**
 * 首页控制器
 */

import Card from '../../../view/site/common/card/card';
import axios from 'axios'
axios.jsonp = (url) => {
  if(!url){
      console.error('请传入一个url参数')
      return;
  }
  return new Promise((resolve,reject) => {
    window.jsonCallBack =(result) => {
      resolve(result)
    }
    var JSONP=document.createElement("script");
    JSONP.type="text/javascript";
    JSONP.src=`${url}?callback=jsonCallBack`;
    document.getElementsByTagName("head")[0].appendChild(JSONP);
    setTimeout(() => {
      document.getElementsByTagName("head")[0].removeChild(JSONP)
    },500)
  })
} 

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

    axios.jsonp('http://cloud.discuz.chat/latest.json')
    .then(response => {  
      console.log(response);
    })
    .catch(error =>{
      console.log(error);
    });
  },

  components:{
    Card
  }
}
