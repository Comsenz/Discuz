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
      
    }
  },
  computed: mapState({
    // searchData:state => state.admin.searchData
  }),

  methods: {
    /*...mapMutations({
      setSearch:'admin/SET_SEARCH_CONDITION'
    }),*/

    closeViewer() {
      this.showViewer = false
    },

    handleCheckAllChange(val) {
      this.checkedTheme = val ? this.themeListAll : [];
      this.isIndeterminate = false;
    },

    handleCheckedCitiesChange(index, id, status) {

      let checkedCount = this.checkedTheme.length;
      this.checkAll = checkedCount === this.themeListAll.length;
      this.isIndeterminate = checkedCount > 0 && checkedCount < this.themeListAll.length;

    },

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
      console.log('请求');
      let searchData = this.searchData;

      this.appFetch({
        url: 'topics',
        method: 'get',
        data: {
          include: ['user'],
          'filter[content]':searchData.topicContent,
          'page[number]': pageNumber,
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
          console.log(this.themeList,'列表');
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
    // 全部删除
    deleteClick() {
      const ids = this.checkedTheme.join(',');
      this.appFetch({
        url: 'deleteTopics',
        method: 'delete',
        splice: '/' + ids,
        }).then(res => {
          this.$message.success("删除成功");
          this.getThemeList();
        })
    },
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

    // this.setSearch(data);
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