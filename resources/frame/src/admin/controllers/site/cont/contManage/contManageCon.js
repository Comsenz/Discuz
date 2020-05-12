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

      toppingRadio: 2,       //是否置顶
      essenceRadio: 2,       //是否精华

      checkAll: false,       //全选状态
      checkAllNum: 0,        //多选打勾数
      themeListAll: [],      //主题列表全部
      checkedTheme: [],      //多选列表初始化
      isIndeterminate: false,//全选不确定状态

      themeList: [],         //主题列表
      currentPag: 1,         //当前页数
      total: 0,              //主题列表总条数
      pageCount: 1,          //总页数
      showViewer: false,     //预览图
      url: [],

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
      },
      searchData: {
        topicTypeId: '0',         //主题类型
        categoryId: 0,            //主题分类ID
        pageSelect: '10',         //每页显示数
        themeAuthor: '',          //主题作者
        themeKeyWords: '',        //主题关键词
        dataValue: ['', ''],      //发表时间范围
        viewedTimesMin: '',       //被浏览次数最小
        viewedTimesMax: '',       //被浏览次数最大
        numberOfRepliesMin: '',   //被回复数最小
        numberOfRepliesMax: '',   //被回复数最大
        essentialTheme: '',       //精华主题类型
        topType: ''               //置顶主题类型
      },
      topicType: [
        {
          name: '全部',
          id: '0'
        },
        {
          name: '置顶主题',
          id: '1'
        }, {
          name: '精华主题',
          id: '2'
        }, {
          name: '置顶并精华主题',
          id: '3'
        }
      ],
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

    imgShowClick(list, imgIndex) {
      this.url = [];
      let urlList = [];

      list.forEach((item) => {
        urlList.push(item._data.url)
      });

      this.url.push(urlList[imgIndex]);

      urlList.forEach((item, index) => {
        if (index > imgIndex) {
          this.url.push(item);
        }
      });

      urlList.forEach((item, index) => {
        if (index < imgIndex) {
          this.url.push(item);
        }
      });

      this.showViewer = true
    },
    closeViewer() {
      this.showViewer = false
    },

    handleCheckAllChange(val) {
      /*if (val){
        this.checkedTheme.forEach((item,index)=>{
          this.checkedTheme[index].id = this.themeList[index].id();
          this.checkedTheme[index].status = true;
          this.checkAllNum = this.checkedTheme.length;
        })
      } else {
        this.checkedTheme.forEach((item,index)=>{
          this.checkedTheme[index].id = this.themeList[index].id();
          this.checkedTheme[index].status = false;
          this.checkAllNum = 0;
        })
      }
      this.isIndeterminate = false;
      */

      this.checkedTheme = val ? this.themeListAll : [];
      this.isIndeterminate = false;
    },

    handleCheckedCitiesChange(index, id, status) {

      let checkedCount = this.checkedTheme.length;
      this.checkAll = checkedCount === this.themeListAll.length;
      this.isIndeterminate = checkedCount > 0 && checkedCount < this.themeListAll.length;

      /*this.checkedTheme[index].id = id;

      let checkLength = this.checkedTheme.length;

      /!*
      * 统计多选打勾数
      * *!/
      this.checkAllNum = status
        ? this.checkAllNum + 1 : this.checkAllNum - 1;

      /!*
      * 如果打勾数大于 0 或小于 主题列表长度，则全选不确定状态打开
      * *!/
      if (this.checkAllNum > 0 && this.checkAllNum < checkLength){
        this.isIndeterminate = true;
      }

      /!*
      * 如果打勾数等于主题列表长度，则全选状态打开，不确定状态关闭
      * *!/
      if (this.checkAllNum === checkLength){
        this.checkAll = true;
        this.isIndeterminate = false;
      }

      /!*
      * 如果打勾数小于1，则全选状态、不确定状态都关闭
      * *!/
      if (this.checkAllNum < 1){
        this.isIndeterminate = false;
        this.checkAll = false;
      }*/

    },

    /*
    * 格式化日期
    * */
    formatDate(data) {
      return this.$dayjs(data).format('YYYY-MM-DD HH:mm')
    },

    submitClick() {
      this.subLoading = true;

      let themeData = [];         //操作主题数据
      let attributes = {};        //操作选项
      let relationships = {
        'category': {
          'data': {
            'id': ''
          }
        }
      };  //主题分类关系
      let selectStatus = false;

      if (this.operatingSelect === 'class') {
        this.checkedTheme.forEach((item, index) => {
          themeData.push(
            {
              'type': 'threads',
              'id': item,
              'attributes': attributes,
              'relationships': relationships
            }
          )
        });
      } else {
        this.checkedTheme.forEach((item, index) => {
          themeData.push(
            {
              'type': 'threads',
              'id': item,
              'attributes': attributes,
            }
          )
        });
      }

      switch (this.operatingSelect) {
        case 'class':
          if (this.categoryId) {
            relationships.category.data.id = this.categoryId;
          } else {
            selectStatus = true;
          }
          break;
        case 'sticky':
          attributes.isSticky = this.toppingRadio === 1 ? true : false;
          break;
        case 'delete':
          attributes.isDeleted = true;
          break;
        case 'marrow':
          attributes.isEssence = this.essenceRadio === 1 ? true : false;
          break;
        default:
          selectStatus = true;
          this.subLoading = false;
          if (themeData.length > 0){
            this.$message({
              showClose: true,
              message: '操作选项错误，请重新选择或刷新页面(F5)',
              type: 'warning'
            });
          }
      }

      /*if (selectStatus){
        this.$message({
          showClose: true,
          message: '操作选项错误，请重新选择或刷新页面(F5)',
          type: 'warning'
        });
      }*/

      if (themeData.length < 1) {
        this.$message({
          showClose: true,
          message: '操作主题列表为空，请选择主题',
          type: 'warning'
        });
      } else if (!selectStatus) {
        this.appFetch({
          url: 'threads',
          splice: '/batch',
          method: 'patch',
          data: { data: themeData }
        }).then(res => {
          this.subLoading = false;
          if (res.errors) {
            this.$message.error(res.errors[0].code);
          } else {
            if (res.meta && res.data) {
              this.checkedTheme = [];
              this.$message.error('操作失败！');
            } else {
              if (this.pageCount < 3) {
                this.currentPag = 1;
                webDb.setLItem('currentPag', 1);
              }
              this.getThemeList(Number(webDb.getLItem('currentPag')) || 1);
              this.isIndeterminate = false;
              this.checkAll = false;
              this.checkedTheme = [];
              this.$message({
                message: '操作成功',
                type: 'success'
              });
            }
          }
        }).catch(err => {
        })
      }

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
      this.searchData.dataValue = this.searchData.dataValue == null ? ['', ''] : this.searchData.dataValue;
      this.currentPag = 1;
      this.getThemeList(1);
    },

    /*
    * 请求接口
    * */
    getThemeList(pageNumber) {
      let searchData = this.searchData;

      this.appFetch({
        url: 'threads',
        method: 'get',
        data: {
          include: ['user', 'firstPost', 'lastPostedUser', 'category', 'firstPost.images', 'threadVideo', 'firstPost.attachments'],
          'filter[isDeleted]': 'no',
          'filter[isApproved]': '1',
          'filter[username]': searchData.themeAuthor,
          'filter[categoryId]': searchData.categoryId,
          'page[number]': pageNumber,
          'page[size]': searchData.pageSelect,
          'filter[q]': searchData.themeKeyWords,
          'filter[createdAtBegin]': searchData.dataValue[0],
          'filter[createdAtEnd]': searchData.dataValue[1],
          'filter[viewCountGt]': searchData.viewedTimesMin,
          'filter[viewCountLt]': searchData.viewedTimesMax,
          'filter[postCountGt]': searchData.numberOfRepliesMin,
          'filter[postCountLt]': searchData.numberOfRepliesMax,
          'filter[isEssence]': searchData.essentialTheme,
          'filter[isSticky]': searchData.topType,
          'sort': '-createdAt'
        }
      }).then(res => {
        if (res.errors) {
          this.$message.error(res.errors[0].code);
        } else {
          this.themeList = res.readdata;
          this.total = res.meta.threadCount;
          this.pageCount = res.meta.pageCount;

          this.themeListAll = [];
          this.themeList.forEach((item, index) => {
            this.themeListAll.push(item._data.id);
          });
        }
      }).catch(err => {
      })
    },
    getCategories() {
      this.appFetch({
        url: 'categories',
        method: 'get',
        data: {}
      }).then(res => {
        if (res.errors) {
          this.$message.error(res.errors[0].code);
        } else {
          res.data.forEach((item, index) => {
            this.categoriesList.push({
              name: item.attributes.name,
              id: item.id
            })
          })
        }
      }).catch(err => {
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
    this.getCategories();
  },

  components: {
    Card,
    ContArrange,
    tableNoList,
    ElImageViewer,
    Page
  }

}
