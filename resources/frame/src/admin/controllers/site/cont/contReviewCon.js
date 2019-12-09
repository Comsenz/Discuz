/*
* 内容审核
* */

import Card from '../../../view/site/common/card/card';
import ContArrange from '../../../view/site/common/cont/contArrange';
import webDb from 'webDbHelper';
import { mapState } from 'vuex';
import moment from "moment/moment";

export default {
  data:function () {
    return {
      searchUserName:'',  //用户名
      keyWords:'',        //关键词

      deleteStatus:true,
      multipleSelection:[],

      checkList: ['通过'],

      showSensitiveWords:false,   //显示敏感词

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
      pageSelect:'10',            //每页显示数选择值选中

      searchReview:[
        {
          value:0,
          label:'未审核'
        },
        {
          value:2,
          label:'以忽略'
        }
      ],
      searchReviewSelect:0,       //审核状态选中

      searchCategory:[
        {
          value:1,
          label:'所有帖子'
        },
        {
          value:2,
          label:'仅群组'
        },
        {
          value:3,
          label:'默认板块'
        },
        {
          value:4,
          label:'新板块名称'
        },

      ],
      searchCategorySelect:1,     //搜索分类选中

      searchTime:[
        {
          value:1,
          label:'全部'
        },
        {
          value:2,
          label:'一周'
        },
        {
          value:3,
          label:'一月'
        },
        {
          value:4,
          label:'三月'
        }
      ],
      searchTimeSelect:1,         //搜索时间选中

      reasonForOperationInput:"",   //操作理由输入框

      reasonForOperation:[
        {
          value:1,
          label:'无'
        },
        {
          value:2,
          label:'广告/SPAM'
        },
        {
          value:3,
          label:'恶意灌水'
        },
        {
          value:4,
          label:'违规内容'
        },
        {
          value:5,
          label:'文不对题'
        },
        {
          value:6,
          label:'重复发帖'
        },
        {
          value:7,
          label:'我很赞同'
        },
        {
          value:8,
          label:'精品文章'
        },
        {
          value:9,
          label:'原创内容'
        },
        {
          value:10,
          label:'其他'
        }
      ],
      reasonForOperationSelect:1,   //操作理由选中

      appleAll:false,             //应用其他页面

      themeList:[],               //主题列表
      currentPag: 1,              //当前页数
      total:0,                    //主题列表总条数
      pageCount:1,                //总页数

      checkedTheme:[],            //多选列表初始化

    }
  },
  computed:mapState({
    searchData:state => state.admin.searchData
  }),

  methods:{
    handleSelectionChange(val) {
      this.multipleSelection = val;

      if (this.multipleSelection.length >= 1){
        this.deleteStatus = false
      } else {
        this.deleteStatus = true
      }

    },

    reasonForOperationChange(val){
      switch (val){
        case 1:
          this.reasonForOperationInput = '无';
          break;
        case 2:
          this.reasonForOperationInput = '广告/SPAM';
          break;
        case 3:
          this.reasonForOperationInput = '恶意灌水';
          break;
        case 4:
          this.reasonForOperationInput = '违规内容';
          break;
        case 5:
          this.reasonForOperationInput = '文不对题';
          break;
        case 6:
          this.reasonForOperationInput = '重复发帖';
          break;
        case 7:
          this.reasonForOperationInput = '我很赞同';
          break;
        case 8:
          this.reasonForOperationInput = '精品文章';
          break;
        case 9:
          this.reasonForOperationInput = '原创内容';
          break;
        case 10:
          this.reasonForOperationInput = '其他';
          break;
      }
    },

    handleSizeChange(val) {
      console.log(`每页 ${val} 条`);
    },

    handleCurrentChange(val) {
      webDb.setLItem('currentPag',val);
      this.isIndeterminate = false;
      this.checkAll = false;
      this.getThemeList(val);
    },

    /*
    * 格式化日期
    * */
    formatDate(data){
      // console.log(this.appCommonH.getStrTime('y',data));
      return moment(data).format('YYYY-MM-DD HH:mm')
    },

    /*
    * 请求接口
    * */
    getThemeList(pageNumber){
      let searchData = this.searchData;
      const params = {
        'filter[isDeleted]':'no',
        'filter[categoryId]':searchData.categoryId,
        'page[number]':pageNumber,
        'page[size]':searchData.pageSelect,
        'filter[q]':searchData.themeKeyWords,
        'filter[createdAtBegin]':searchData.dataValue[0],
        'filter[createdAtEnd]':searchData.dataValue[1],
        'filter[viewCountGt]':searchData.viewedTimesMin,
        'filter[viewCountLt]':searchData.viewedTimesMax,
        'filter[postCountGt]':searchData.numberOfRepliesMin,
        'filter[postCountLt]':searchData.numberOfRepliesMax,
        'filter[isEssence]':searchData.essentialTheme,
        'filter[isSticky]':searchData.topType
      };
      params.include = 'category,lastPostedUser,user,firstPost,lastThreePosts,lastThreePosts.user,firstPost.likedUsers,rewardedUsers';
      this.apiStore.find('threads', params).then(data => {
        this.themeList = data;
        this.total = data.payload.meta.threadCount;
        this.pageCount = data.payload.meta.pageCount;

        /*初始化主题多选框列表*/
        this.checkedTheme = [];
        data.forEach(()=>{
          this.checkedTheme.push({
            id:'',
            passing:false,
            delete:false,
            ignore:false
          })
        });
      });
    },
  },
  created(){
    this.getThemeList(Number(webDb.getLItem('currentPag'))||1);

  },
  components:{
    Card,
    ContArrange
  }

}
