/**
 * pc 端首页控制器
 */
import appCommonH from '../../../../../../helpers/commonHelper';
import browserDb from '../../../../../../helpers/webDbHelper';
import {ImagePreview} from "vant";
export default {
	data: function() {
		return {
      firstpostImageList: [],
      userId: ''
		}
  },
  props: {
    themeCon: { // 组件的list
      type: Object
    },
    firstpostImageListProp: {
      type: Array
    },
  },
  created:function(){
    var u = navigator.userAgent;
    this.isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1; //android终端
    this.isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
    this.isWeixin = appCommonH.isWeixin().isWeixin;
    this.isPhone = appCommonH.isWeixin().isPhone;
    this.userId = browserDb.getLItem('tokenId');
  },
  computed: {
    
  },
	methods: {
    //判断设备，下载时提示
    downAttachment(url){
      if(this.isiOS){
        this.$message('因iphone系统限制，您的手机无法下载文件。请使用安卓手机或电脑访问下载');
      }
    },
    //点击用户名称，跳转到用户主页
    jumpPerDet:function(id){
      // if(!this.userId){
      //   this.$router.push({
      //     path:'/login-user',
      //     name:'login-user'
      //   })
      // } else {
      this.$router.push({ path:'/home-page'+'/'+id});
      // }
    },

    imageSwiper(imgIndex, typeclick, replyItem) {
      ImagePreview({
        images:this.firstpostImageListProp,
        startPosition:imgIndex,    //图片预览起始位置索引 默认 0
        showIndex: true,    //是否显示页码         默认 true
        showIndicators: true, //是否显示轮播指示器 默认 false
        loop:true,            //是否开启循环播放  貌似循环播放是不起作用的。。。
        closeOnPopstate: true
        
      })
    },

	},

	mounted: function() {
		
		
	},
	beforeRouteLeave (to, from, next) {
	   // window.removeEventListener('scroll', this.handleTabFix, true)
	   next()
	}
}
