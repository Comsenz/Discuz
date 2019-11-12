/**
 * 后台Index
 */


export default {
  data:function () {
    return {
      activeIndex: '1',
      navList:[
        {
          id:0,
          name:'首页'
        },
        {
          id:1,
          name:'全局'
        },
        {
          id:2,
          name:'用户'
        },
        {
          id:3,
          name:'内容'
        },
        {
          id:4,
          name:'财务'
        }
      ],
      indexTitle:"管理中心首页",
      sideTitle:"首页"
    }
  },
  methods:{
    menuClick(title){
      this.sideTitle = title;
    }
  }
}
