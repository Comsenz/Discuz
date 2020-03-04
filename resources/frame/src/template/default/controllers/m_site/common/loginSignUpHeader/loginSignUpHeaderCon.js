// import {Bus} from '../../../../store/site/bus.js';
import appCommonH from '../../../../../../helpers/commonHelper';
import appConfig from "../../../../../../../../frame/config/appConfig";
export default {
  data:function () {
    return {
      headerTitle:this.title,
      // menuIconShow: this.menuIconShow,
      pageName:"",
      popupShow:false,
      // avatarUrl:'',
      // username:'',
      // mobile:''
      isWeixin: false,
      isPhone: false,
      viewportWidth: '',

    }
  },
  props:{
    title:{
      default:'',
      type:String,
    },
    menuIconShow: { // 组件是否显示菜单按钮
      menuIconShow: false
    }
  },
  created() {
    this.isWeixin = appCommonH.isWeixin().isWeixin;
    this.isPhone = appCommonH.isWeixin().isPhone;
    this.viewportWidth = window.innerWidth;
  },
  methods:{
    //设置Header在pc里的宽度
    limitWidth(){
      document.getElementById('comHeader').style.width = "640px";
      let viewportWidth = window.innerWidth;
      document.getElementById('comHeader').style.marginLeft = (viewportWidth - 640)/2+'px';
    },
    showPopup() {
      //侧边栏显示
      this.popupShow = true;
    },
    headerBack(){
      let backGo = this.$route.query.backGo;

      // this.$router.go(-1);

      // console.log('回退');

      // this.$router.back(-1);

      // alert(window.history.length);
      if(document.referrer == '' && (window.history.length == 0 || window.history.length < 3)){
        // alert('上一级为空时');
        window.location.href = appConfig.baseUrl;
      } else {
        // alert('有上一级');
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

  mounted () {
    //设置在pc的宽度
    if(this.isWeixin != true && this.isPhone != true){
      this.limitWidth();
    }
  },

}
