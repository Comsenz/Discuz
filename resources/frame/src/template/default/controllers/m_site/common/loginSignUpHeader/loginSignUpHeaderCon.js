import {Bus} from '../../../../store/site/bus.js';
export default {
  data:function () {
    return {
      headerTitle:this.title,
      // menuIconShow: this.menuIconShow,
      pageName:"",

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
  methods:{
    headerBack(){
      console.log("回退");
      this.$router.go(-1)
    }
  },

  mounted (){
    /*this.pageName = this.$router.history.current.name;
    if (this.pageName === 'modify-data'){
      this.headerTitle="修改资料"
    }*/
  }

}
