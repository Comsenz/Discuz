/*
* 举报管理-已处理记录控制器
* */
import ContArrange from '@/admin/view/site/common/cont/contArrange';
import Page from '@/admin/view/site/common/page/page';
import tableNoList from '@/admin/view/site/common/table/tableNoList';
import webDb from 'webDbHelper';


export default {
  data:function() {
    return {
      isIndeterminate: false,  // 全选不确定状态
      checkAll: false,         // 全选状态
      reportListAll: [],       //  举报列表全选
      checkedReport: [],       //已选举报列表
      reportList: [],          // 举报列表数据
      pageData: {
        pageSize: 10,           // 每页显示数
        pageCount: 1,          // 总页数
        pageNumber: 1,         //当前页
        pageTotal: 0,          // 举报列表总条数
      },
      searchData: {
        userName: '',          // 举报人用户名
        reportType: null,      // 举报类型（主题、评论、个人主页）
        reportTime: ['', ''],  // 举报时间范围
        status: 0,             //是否已处理 0 否 1 是
      },
      reportTypeData: [ 
        // {
        //   name: '个人主页',
        //   id: 0
        // },
        {
          name: '主题',
          id: 1
        }, {
          name: '评论/回复',
          id: 2
        }
      ],
      subLoading:false,       // 全部删除按钮状态
      visible: false,
    }
  },

  methods: {
    /*
    * 举报列表全选状态切换
    * */
   closeDelet(index) {
    this.$refs[index][0].doClose();
  },
    handleCheckAllChange(val) {
      this.checkedReport = val ? this.reportListAll : [];
      this.isIndeterminate = false;
    },
    /*
    * 举报列表单选状态
    * */
    handleCheckedCitiesChange() {
      let checkedCount = this.checkedReport.length;
      this.checkAll = checkedCount === this.reportListAll.length;
      this.isIndeterminate = checkedCount > 0 && checkedCount < this.reportListAll.length;
    },
    /*
    * 格式化日期
    * */
    formatDate(data){
      return this.$dayjs(data).format('YYYY-MM-DD HH:mm')
    },
    /*
    * 获取类型
    * */
    getType(type) {
      if(type === 0){
        return '个人主页';
      }else if(type === 1){
        return '主题';
      }else if(type === 2){
        return '评论/回复';
      }
    },
    /*
    * 获取页面地址
    * */
    getUrl(userID, threadID, postID){
      let originUrl = window.origin;
      let href = '';
      if(postID === 0) {
        if(threadID === 0){
          href = '/pagthreadIDes/profile/index?userId=' + userID;
        }else{
          href = '/topic/index?id=' + threadID;
        }
      }else{
        href = '/pages/topic/comment?threadId=' + threadID + '&commentId=' + postID;
      }
      return {
        href,
        url: originUrl + href
      }
    },
    /*
    * 搜索
    **/
    searchClick(){
      this.searchData.reportTime = this.searchData.reportTime == null ? ['', ''] : this.searchData.reportTime;
      this.searchData.reportType = this.searchData.reportType === '' ? null : this.searchData.reportType;
      this.pageData.pageNumber = 1;
      this.getReportList(1);
    },
    /*
    * 获取举报数据
    **/
    getReportList(pageNumber){
      let searchData = this.searchData;
      this.appFetch({
        url: 'reports',
        method: 'get',
        data: {
          "filter[username]": searchData.userName,
          "filter[status]": 1,
          "filter[type]": searchData.reportType,
          "filter[start_time]": searchData.reportTime[0],
          "filter[end_time]":  searchData.reportTime[1],
          "page[number]": pageNumber,
          "page[limit]": this.pageData.pageSize
        }
      }).then(res => {
        if (res.errors) {
          this.$message.error(res.errors[0].code);
        } else {
          this.reportList = res.readdata;
          this.pageData.pageTotal = res.meta.total;
          this.pageData.pageCount = res.meta.pageCount;
          this.reportListAll = [];
          this.reportList.forEach(item => {
            this.reportListAll.push(item._data.id);
          })
        }
      })
    },
    /*
    * 切换页码
    **/
    handleCurrentChange(num) {
      this.checkedReport = [];
      this.pageData.pageNumber = num;
      this.getReportList(num);
    },
    /*
    * 删除操作
    **/
    deleteOperation(type, id) {
      let that = this;
      let userID = '';
      if(type === 1){
        userID = id;
      }else{
        if(this.checkedReport.length < 1){
          this.$message({
            showClose: true,
            message: '操作举报列表为空，请选择举报信息',
            type: 'warning'
          });
          return;
        }
        this.subLoading = true;
        userID = this.checkedReport.toString();
      }
      this.appFetch({
        url: 'reportsBatch',
        splice: '/' + userID,
        method: 'delete'
      }).then(res => {
        console.log('删除',res);
      })
      var time = setTimeout(function(){
        that.subLoading = false;
        that.$message({
          message: '删除成功',
          type: 'success'
        });
        if(type !== 1){
          that.checkAll = false;
        }
        that.getReportList(Number(webDb.getLItem('pageNumber')) || 1) 
      },300)
    },
    getCreated(state){
      if(state){
        this.getReportList(1);
      } else {
        this.getReportList(Number(webDb.getLItem('pageNumber'))||1);
      }
    }
  },
 
  created(){
    this.getUrl();
  },
  beforeDestroy() {
    webDb.setLItem('pageNumber', 1);
  },
  beforeRouteEnter(to,from,next){
    next(vm => {
      if (to.name !== from.name && from.name !== null){
        vm.getCreated(true)
      }else {
        vm.getCreated(false)
      }
    })
  },
  components:{
    ContArrange,
    Page,
    tableNoList
  }
}