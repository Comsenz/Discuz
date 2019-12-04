
import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      categoriesList: [],   //主题分类列表
      categoryId: '',  //主题分类选中

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
      pageSelect:'10',    //每页显示数选择值

      themeAuthor:'',    //主题作者
      themeKeyWords:'',   //主题关键词

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
      },    //快捷选择时间
      dataValue:'',     //发表时间范围

      viewedTimesMin:'',    //被浏览次数最小
      viewedTimesMax:'',    //被浏览次数最大

      numberOfRepliesMin:'',    //被回复数最小
      numberOfRepliesMax:'',    //被回复数最大

      replyType:3,   //被回复数介于类型
      topType:6    //置顶主题类型

    }
  },
  methods:{
    checkboxChange(str){
      setTimeout(()=>{
        if (str){
          let gd =  document.getElementsByClassName('index-main-con__main')[0];
          gd.scrollTo(0,gd.scrollHeight);
        }
      },300);
    },

    submitClick(){
      console.log(this.categoryId);
      console.log(this.pageSelect);
      console.log(this.themeAuthor);
      console.log(this.themeKeyWords);

      console.log(this.dataValue);

      console.log(this.viewedTimesMin + ':::' + this.viewedTimesMax);
      console.log(this.numberOfRepliesMin + ':::' + this.numberOfRepliesMax);

      console.log(this.replyType);
      console.log(this.topType);

      this.getThemeList();

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
        res.data.forEach((item,index)=>{
          this.categoriesList.push({
            name:item.attributes.name,
            id:item.id
          })
        });
      }).catch(err=>{
        console.log(err);
      })

    },
    getThemeList(pageNumber){
      const params = {
        'filter[isDeleted]':'no',
        'page[number]':pageNumber,
        'page[size]':this.pageSelect,
        'filter[q]':this.themeKeyWords,
        'filter[postCountGt]':this.numberOfRepliesMax,
        'filter[postCountLt]':this.numberOfRepliesMin
      };
      params.include = 'user,firstPost,lastThreePosts,lastThreePosts.user,firstPost.likedUsers,rewardedUsers';
      this.apiStore.find('threads', params).then(data => {
        console.log(data);

        // this.themeList = data;
        // this.total = data.payload.meta.threadCount;

        // console.log(data.length);

        /*初始化主题多选框列表*/
        // data.forEach(()=>{
        //   this.checkedTheme.push({
        //     id:'',
        //     status:false
        //   })
        // });

      });
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
