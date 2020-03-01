/*
* 内容管理：最新回复控制器
* */

import ContArrange from '../../../../view/site/common/cont/contArrange';
import tableNoList from '../../../../view/site/common/table/tableNoList'
import Page from '../../../../view/site/common/page/page';
import ElImageViewer from 'element-ui/packages/image/src/image-viewer'
import webDb from 'webDbHelper';
import moment from 'moment';


export default {
  data:function () {
    return {
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
      searchData:{
        pageSelect:'10',         //每页显示数
        themeAuthor:'',          //主题作者
        themeKeyWords:'',        //主题关键词
        dataValue:['',''],            //发表时间范围
      },

      checkAll: false,      //全选状态
      checkAllNum:0,        //多选打勾数
      themeListAll : [],    //主题列表全部
      checkedTheme:[],      //多选列表初始化
      isIndeterminate: false,   //全选不确定状态

      themeList:[],         //主题列表
      currentPag: 1,        //当前页数
      total:0,              //主题列表总条数
      pageCount:1,          //总页数
      showViewer:false,     //预览图

    }
  },
  methods:{
    searchClick(){
      //处理时间为空
      this.searchData.dataValue = this.searchData.dataValue == null?['','']:this.searchData.dataValue;
      this.currentPag = 1;
      this.getPostsList(1);
    },

    /*
    * 格式化日期
    * */
    formatDate(data){
      return moment(data).format('YYYY-MM-DD HH:mm')
    },

    handleCheckAllChange(val){
      this.checkedTheme = val ? this.themeListAll : [];
      this.isIndeterminate = false;
    },

    handleCurrentChange(val){
      document.getElementsByClassName('index-main-con__main')[0].scrollTop = 0;
      this.isIndeterminate = false;
      this.currentPag = val;
      this.checkAll = false;
      this.getPostsList(val);
    },

    deleteAllClick(){
      let deleList = [];
      this.themeList.forEach((item,index)=>{
        deleList.push(item._data.id)
      });

      this.deletedPostsBatch(deleList.join(','))
    },

    imgShowClick(list,imgIndex){
      this.url = [];
      let urlList = [];

      list.forEach((item)=>{
        urlList.push(item._data.url)
      });

      this.url.push(urlList[imgIndex]);

      urlList.forEach((item,index)=>{
        if (index > imgIndex){
          this.url.push(item);
        }
      });

      urlList.forEach((item,index)=>{
        if (index < imgIndex){
          this.url.push(item);
        }
      });

      this.showViewer = true
    },
    closeViewer() {
      this.showViewer = false
    },

    singleOperationSubmit(val,firstPostId){
      switch (val){
        case 1:
          this.deletedPosts(firstPostId);
          break;
        case 2:
          break;
        default:
          console.log("系统错误，请刷新页面");
      }
    },

    /*
    * 接口请求
    * */
    getPostsList(pageNumber){
      let searchData = this.searchData;
      this.appFetch({
        url:'posts',
        method:'get',
        data:{
          include: ['user','thread','thread.category','thread.firstPost','images'],
          'filter[isDeleted]':'no',
          'filter[isApproved]':'1',
          'filter[username]':searchData.themeAuthor,
          'page[number]':pageNumber,
          'page[size]':searchData.pageSelect,
          'filter[q]':searchData.themeKeyWords,
          'filter[createdAtBegin]':searchData.dataValue[0],
          'filter[createdAtEnd]':searchData.dataValue[1],
          'sort':'-createdAt'
        }
      }).then(res=>{
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          this.themeList = res.readdata;
          this.total = res.meta.postCount;
          this.pageCount = res.meta.pageCount;

          this.themeListAll = [];
          this.themeList.forEach((item, index) => {
            this.themeListAll.push(item._data.id);
          });
        }
      }).catch(err=>{
        console.log(err);
      })
    },
    deletedPostsBatch(data){
      this.appFetch({
        url:'postBatch',
        method:'delete',
        splice:'/' + data,
      }).then(res=>{
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          this.getPostsList(Number(webDb.getLItem('currentPag')) || 1);
          this.$message({
            message: '操作成功',
            type: 'success'
          });
        }
      }).catch(err=>{
        console.log(err);
      })
    },
    deletedPosts(id){
      this.appFetch({
        url:'postBatch',
        method:'delete',
        splice:'/' + id
      }).then(res=>{
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          this.getPostsList(Number(webDb.getLItem('currentPag')) || 1);
          this.$message({
            message: '操作成功',
            type: 'success'
          });
        }
      })
    }

  },
  created(){
    this.currentPag = Number(webDb.getLItem('currentPag'))||1;
    this.getPostsList(Number(webDb.getLItem('currentPag'))||1);
  },
  components:{
    ContArrange,
    tableNoList,
    ElImageViewer,
    Page
  }
}
