/*
* 内容审核
* */

import Card from '../../../view/site/common/card/card';
import ContArrange from '../../../view/site/common/cont/contArrange';
import Page from '../../../view/site/common/page/page';

export default {
  data:function () {
    return {
      tableData: [{
        className: '站长',
        sort: '1',
        classIntroduction:'分类介绍分类介绍分类介绍分类介绍分类介绍分类介绍'
      }, {
        className: '攻城狮',
        sort: '2',
        classIntroduction:'分类介绍分类介绍分类介绍分类介绍分类介绍分类介绍'
      }, {
        className: '版主',
        sort: '3',
        classIntroduction:'分类介绍分类介绍分类介绍分类介绍分类介绍分类介绍'
      }],

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

      searchData:[
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
      searchDataSelect:1,         //搜索时间选中

      reasonForOperation:[
        {
          value:1,
          label:'无'
        },
        {
          value:0,
          label:'--------'
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
          label:''
        }
      ],
      reasonForOperationSelect:1,    //操作理由选中

      appleAll:false,

      reasonForOperationInput:'',

      currentPaga: 1,

    }
  },

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

    handleCurrentChange(val) {
      console.log(val);
      // webDb.setLItem('currentPag',val);
      // this.isIndeterminate = false;
      // this.checkAll = false;
      // this.getThemeList(val);
    },

    createdFn(page){
      return page
    },

  },

  components:{
    Card,
    ContArrange,
    Page
  }

}
