
import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';
import { mapMutations,mapState } from 'vuex';

export default {
  data:function () {
    return {
      categoriesList: [
        {
          name:'全部',
          id:''
        }
      ],   //主题分类列表
      categoryId: '',       //主题分类选中

      pageOptions: [
        {
          value: '10',
          label: '每页显示10条'
        }, {
          value: '20',
          label: '每页显示20条'
        }, {
          value: '30',
          label: '每页显示30条'
        }
      ],
      pageSelect:'10',      //每页显示数选择值

      themeAuthor:'',       //主题作者
      themeKeyWords:'',     //主题关键词

      checkedStatus:false,  //更多选项

      pickerOptions: {
        shortcuts: [{
          text: '最近一周',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
            picker.$emit('pick', [start, end]);
          }
        }, {
          text: '最近一个月',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
            picker.$emit('pick', [start, end]);
          }
        }, {
          text: '最近三个月',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
            picker.$emit('pick', [start, end]);
          }
        }]
      }, //快捷选择时间
      dataValue:['',''],         //发表时间范围

      viewedTimesMin:'',    //被浏览次数最小
      viewedTimesMax:'',    //被浏览次数最大

      numberOfRepliesMin:'',    //被回复数最小
      numberOfRepliesMax:'',    //被回复数最大

      essentialTheme:'',    //精华主题类型
      topType:''            //置顶主题类型

    }
  },
  /*
  * 映射index/state内定义的属性
  * */
  computed:mapState({
    searchData:state => state.admin.searchData,

    //扩展用法：映射函数可以处理一些数据返回，在别的地方this.直接调用
    countSearchData(state){
      return state.admin.pageSelect + 10
    }
  }),
  methods:{
    /*
    * 映射mutation内方法
    * */
    ...mapMutations({
      setSearch:'admin/SET_SEARCH_CONDITION'
    }),

    checkboxChange(str){
      setTimeout(()=>{
        if (str){
          let gd =  document.getElementsByClassName('index-main-con__main')[0];
          gd.scrollTo(0,gd.scrollHeight);
        }
      },300);
    },

    submitClick(){
      this.dataValue = this.dataValue == null?['','']:this.dataValue;

      console.log(this.dataValue);

      this.dataValue[0] = this.dataValue[0] == ''?this.dataValue[0]:this.dataValue[0] + '-00-00-00';
      this.dataValue[1] = this.dataValue[1] == ''?this.dataValue[1]:this.dataValue[1] + '-23-59-59';

      console.log(this.dataValue);

      /*
      * 调用方法可以在里面传值，对应mutations里对应方法形参payload
      * */
      this.setSearch({
        categoryId:this.categoryId,           //主题分类ID
        pageSelect:this.pageSelect,           //每页显示数
        themeAuthor:this.themeAuthor,         //主题作者
        themeKeyWords:this.themeKeyWords,     //主题关键词
        dataValue:this.dataValue,             //发表时间范围
        viewedTimesMin:this.viewedTimesMin,   //被浏览次数最小
        viewedTimesMax:this.viewedTimesMax,   //被浏览次数最大
        numberOfRepliesMin:this.numberOfRepliesMin,   //被回复数最小
        numberOfRepliesMax:this.numberOfRepliesMax,   //被回复数最大
        essentialTheme:this.essentialTheme,             //精华主题类型
        topType:this.topType                  //置顶主题类型
      });

      this.$router.push({path:'/admin/cont-manage'});

      /*
      * 读取映射state内的数据
      * */
      console.log(this.searchData);


    },

    /*
    * 请求接口
    * */
    getCategories(){
      this.appFetch({
        url:'categories',
        method:'get',
        data:{}
      }).then(res=>{
        if (res.error){
          this.$message.error(res.errors[0].code);
        }else {
          res.data.forEach((item, index) => {
            this.categoriesList.push({
              name: item.attributes.name,
              id: item.id
            })
          });
        }
      }).catch(err=>{
        console.log(err);
      })

    },

  },
  created(){
    this.getCategories();
  },
  components:{
    Card,
    CardRow
  }
}
