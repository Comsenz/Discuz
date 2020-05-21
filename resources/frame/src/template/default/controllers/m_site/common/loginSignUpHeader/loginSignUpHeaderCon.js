// import {Bus} from '../../../../store/site/bus.js';
import appCommonH from '../../../../../../helpers/commonHelper';
import appConfig from "../../../../../../../../frame/config/appConfig";
import browserDb from '../../../../../../helpers/webDbHelper';
export default {
  data: function () {
    return {
      headerTitle: this.title,
      // menuIconShow: this.menuIconShow,
      pageName: "",
      popupShow: false,
      // avatarUrl:'',
      // username:'',
      // mobile:''
      isWeixin: false,
      isPhone: false,
      viewportWidth: '',
      prevRoute: null
    }
  },
  props: {
    title: {
      default: '',
      type: String,
    },
    menuIconShow: { // 组件是否显示菜单按钮
      menuIconShow: false
    }
  },
  created() {
    this.isWeixin = appCommonH.isWeixin().isWeixin;
    this.isPhone = appCommonH.isWeixin().isPhone;
    this.viewportWidth = window.innerWidth;
    this.prevRoute = browserDb.getLItem('prevRoute');
  },
  methods: {
    //设置Header在pc里的宽度
    limitWidth() {
      document.getElementById('comHeader').style.width = "640px";
      let viewportWidth = window.innerWidth;
      document.getElementById('comHeader').style.marginLeft = (viewportWidth - 640) / 2 + 'px';
    },
    showPopup() {
      //侧边栏显示
      this.popupShow = true;
    },
    goHome() {
      this.$router.push("/");
    },
    headerBack() {
      let backGo = this.$route.query.backGo;
      const sidePageList = ['my-wallet', 'modify-data'];
      const noGobackList = [
        'pay-circle-login', 'login-user', 'login-phone', 'wx-login-bd', 'wx-sign-up-bd', 
        'welink-login-bd', 'welink-sign-up-bd', 'sign-up'];

      if (sidePageList.includes(this.$route.name)) {
        this.$router.push('/');
        return;
      }
      if (noGobackList.includes(this.prevRoute)) {
        this.$router.push("/");
        return;
      }

      if (document.referrer == '' && window.history.length == 0) {
        this.$router.push("/");
      } else {
        this.$router.go(-1);
      }


      /*if (backGo){
        if (isNaN(parseInt(backGo))){
          this.$router.push({path:backGo})
        } else {
          this.$router.go(this.$route.query.backGo)
        }
      } else {
        // if(window.history.go(-1) == '' || window.history.go(-1) == null || !window.history.go(-1)){
          // alert(window.history.length);
          if(document.referrer == '' && (window.history.length === 0)){
            // alert('上一级为空时');
          window.location.href = appConfig.baseUrl;
        } else {
          // alert('有上一级');
          this.$router.go(-1);
        }
      }*/

    }
  },

  mounted() {
    //设置在pc的宽度
    if (this.isWeixin != true && this.isPhone != true) {
      this.limitWidth();
    }
  }
}
