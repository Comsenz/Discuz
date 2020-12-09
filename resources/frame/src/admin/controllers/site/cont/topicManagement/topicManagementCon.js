/*
* 内容分类控制器
* */

import Card from '../../../../view/site/common/card/card';
import ContArrange from '../../../../view/site/common/cont/contArrange';
import tableNoList from '../../../../view/site/common/table/tableNoList'
import Page from '../../../../view/site/common/page/page';
import webDb from 'webDbHelper';
import { mapState, mapMutations } from 'vuex';
import ElImageViewer from 'element-ui/packages/image/src/image-viewer'
import fa from "element-ui/src/locale/lang/fa";

export default {
  data: function () {
    return {
      operatingList: [
        {
          name: '批量移动到分类',
          label: 'class'
        },
        {
          name: '批量置顶',
          label: 'sticky'
        },
        {
          name: '批量删除',
          label: 'delete'
        },
        {
          name: '批量设置精华',
          label: 'marrow'
        }
      ],  //操作列表
      operatingSelect: '',   //操作单选选择

      categoriesList: [
        {
          name: '所有分类',
          id: 0
        }
      ], //选择站点列表
      categoryId: '',        //选择站点选中

      checkAll: false,       //全选状态
      checkAllNum: 0,        //多选打勾数
      themeListAll: [],      //主题列表全部
      checkedTheme: [],      //多选列表初始化
      isIndeterminate: false,//全选不确定状态

      themeList: [],         //话题列表
      currentPag: 1,         //当前页数
      total: 0,              //主题列表总条数
      pageCount: 1,          //总页数
      showViewer: false,     //预览图
      url: [],

      searchData: {
        topicContent: '',         //话题内容
        pageSelect: '10',         //每页显示数
        topicAuthor: '',          //话题作者
        releaseTime: ['',''],     //创建时间范围
        numberOfThreadMin: '',    //主题数起始值
        numberOfThreadMax: '',    //主题数结束值
        numberOfHotMin: '',       //热度数起始值
        numberOfHotMax: '',       //热度数结束值
      },
      subLoading:false,     //提交按钮状态
      
      recommentbtn: true,
      recomment1: '推荐',
      recomment2: '取消推荐',
      options: [
        {
          value: 1,
          label: '是',
        },
        {
          value: 0,
          label: '否',
        }
      ],
      value: '',
      checkedAll: false,
      recommentNumber: '',
      recommentParams: 'createdAt',
      radio: [],
      themeOperation: [],
      themeOperations: [],
      recommend: [],
      cancelrecomend: [],
      detelethem: [],
      sobj:'',
      visible: false,
    }
  },
  computed: mapState({
    // searchData:state => state.admin.searchData
  }),

  methods: {
    /*...mapMutations({
      setSearch:'admin/SET_SEARCH_CONDITION'
    }),*/
    closeDelet(index) {
      this.$refs[index][0].doClose();
    },
    closeViewer() {
      this.showViewer = false
    },

    // handleCheckAllChange(val) {
    //   this.checkedTheme = val ? this.themeListAll : [];
    //   this.isIndeterminate = false;
    // },

    // handleCheckedCitiesChange(index, id, status) {

    //   let checkedCount = this.checkedTheme.length;
    //   this.checkAll = checkedCount === this.themeListAll.length;
    //   this.isIndeterminate = checkedCount > 0 && checkedCount < this.themeListAll.length;

    // },

    /*
    * 格式化日期
    * */
    formatDate(data) {
      return this.$dayjs(data).format('YYYY-MM-DD HH:mm')
    },

    handleCurrentChange(val) {
      document.getElementsByClassName('index-main-con__main')[0].scrollTop = 0;
      this.isIndeterminate = false;
      this.currentPag = val;
      this.checkAll = false;
      this.checkedTheme = [];
      this.getThemeList(val);
    },

    searchClick() {
      //判断主题类型
      switch (this.searchData.topicTypeId) {
        case '0':
          this.searchData.essentialTheme = '';
          this.searchData.topType = '';
          break;
        case '1':
          this.searchData.essentialTheme = '';
          this.searchData.topType = 'yes';
          break;
        case '2':
          this.searchData.essentialTheme = 'yes';
          this.searchData.topType = '';
          break;
        case '3':
          this.searchData.essentialTheme = 'yes';
          this.searchData.topType = 'yes';
          break;
      }

      //处理时间为空
      this.searchData.releaseTime = this.searchData.releaseTime == null ? ['', ''] : this.searchData.releaseTime;
      this.currentPag = 1;
      this.getThemeList(1);
    },

    /*
    * 请求接口
    * */
    getThemeList(pageNumber) {
      let searchData = this.searchData;
      this.appFetch({
        url: 'topics',
        method: 'get',
        data: {
          include: ['user'],
          'filter[content]':searchData.topicContent,
          'page[number]': pageNumber,
          'filter[recommended]': this.value,
          'page[size]': searchData.pageSelect,
          'filter[q]': searchData.themeKeyWords,
          'sort': '-createdAt',
          'filter[username]':searchData.topicAuthor,
          'filter[content]':searchData.topicContent,
          'filter[createdAtBegin]':searchData.releaseTime[0],
          'filter[createdAtEnd]':searchData.releaseTime[1],
          'filter[threadCountBegin]':searchData.numberOfThreadMin,
          'filter[threadCountEnd]':searchData.numberOfThreadMax,
          'filter[viewCountBegin]':searchData.numberOfHotMin,
          'filter[viewCountEnd]':searchData.numberOfHotMax,
        }
      }).then(res => {
        if (res.errors) {
          this.$message.error(res.errors[0].code);
        } else {
          this.themeList = res.readdata;
          this.total = res.meta.total;
          this.pageCount = res.meta.pageCount;

          this.themeListAll = [];
          this.themeList.forEach((item, index) => {
            this.themeListAll.push(item._data.id);
          });
        }
      }).catch(err => {
      })
    },

    /**
     * 删除话题
     */
    deteleTopic(id) {
      this.appFetch({
        url: 'topics',
        method: 'delete',
        splice: '/' + id,
        }).then(res => {
          this.$message.success("删除成功");
          this.getThemeList();
        })
    },

    // 全部删除
    deleteClick(ids, nums) {
      const whole = ids.join(',');
      this.appFetch({
        url: 'deleteTopics',
        method: 'delete',
        splice: '/' + ids,
        }).then(res => {
          if(nums === 1) {
            this.$message.success("删除成功");
          }
          this.getThemeList();
        })
    },
    
    // 推荐
    recommentBtn() {
      this.recommentbtn = !this.recommentbtn;
    },
    
    /**
     * 推荐事件
    */
    btnrecomment(id,comment){
      if(comment) {
        this.recommentNumber = 0;
      } else {
        this.recommentNumber = 1;
      }
      this.appFetch({
        url: `topics`,
        splice: '/' + id,
        method: "patch",
        data:{
          "data": {
            "type": "topics",
            "attributes": {
              "recommended": this.recommentNumber,
            }
          }      
        }
      })
      .then((res) => {
        if(res.data.attributes.recommended === 1) {
          this.$message.success("推荐成功");
        } else {
          this.$message.success("取消推荐成功");
        }
        this.getThemeList();
      })
    },
    
    /**
     *全部推荐 
     */
    allRecomment(num,isds, nums) {
      const whole = isds.join(',');
      this.appFetch({
        url: 'deleteTopics',
        method: "patch",
        splice: '/' + whole,
        data:{
          data: {
            ids: whole,
            type: "topics",
            attributes: {
              "recommended": num,
            }
          }      
        }
      })
      .then((res) => {
        if(nums === 1) {
          if (num === 1) {
            this.$message.success("全部推荐成功");
          } else {
            this.$message.success("全部取消推荐成功");
          }
        }
        this.getThemeList();
      })
    },

    /**
     * 全部选中
    */
    btninformation(res) {
      this.checkedAll = res;
    },
    /**
     * 获取提交id
    */
    themidpost(e,res) {
      const obj = {type: e, themid: res};
      if(this.themeOperations.indexOf(res) === -1) {
        this.themeOperations.push(res);
        this.themeOperation.push({type: e, themid: res});
      } else {
        this.themeOperation.forEach((value,index) => {
          if(value.themid == res) {
            this.themeOperation[index].type = e;       
          }
        })
      }
    },

    /**
      * 点击提交
    */
    btnSubmit() {
      this.recommend = [];
      this.cancelrecomend = [];
      this.detelethem = [];
      console.log(this.themeOperation);
      this.themeOperation.forEach((value,index) => {
        if (value.type === 1) {
          this.recommend.push(value.themid);
        } else if(value.type === 2) {
          this.cancelrecomend.push(value.themid);
        } else if (value.type === 3) {
          this.detelethem.push(value.themid);
        }
      })
      if(this.recommend.length >= 1) {
        this.allRecomment(1,this.recommend, 2);
      }
      if(this.detelethem.length >= 1) {
        this.deleteClick(this.detelethem, 2);
      }
      if(this.cancelrecomend.length >= 1) {
        this.allRecomment(0,this.cancelrecomend, 2);
      }
      this.$message.success("提交成功");
      this.radio = [];
      this.themeOperation = [];
    }
  },

  beforeDestroy() {
    webDb.setLItem('currentPag', 1);

    let data = new Object();

    for (let key in this.searchData) {
      if (key === 'pageSelect') {
        data[key] = '10'
      } else {
        data[key] = ''
      }
    }
  },

  created() {
    this.currentPag = Number(webDb.getLItem('currentPag')) || 1;
    this.getThemeList(Number(webDb.getLItem('currentPag')) || 1);
  },

  components: {
    Card,
    ContArrange,
    tableNoList,
    ElImageViewer,
    Page
  }

}