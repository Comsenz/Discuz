// import {Bus} from '../../../../store/site/bus.js';
import appCommonH from '../../../../../../helpers/commonHelper';
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
      isPhone: false

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
  },
  mounted () {
    //设置在pc的宽度
    if(this.isWeixin != true && this.isPhone != true){
      this.limitWidth();
    }
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
      console.log("回退");
      this.$router.go(-1)
    }
  },

  mounted () {
    //设置在pc的宽度
    if(this.isWeixin != true && this.isPhone != true){
      this.limitWidth();
    }
  },
  beforeRouteLeave (to, from, next) {
    next()
  }

}
