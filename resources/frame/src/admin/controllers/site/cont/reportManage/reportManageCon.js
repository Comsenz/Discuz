/*
* 举报管理-最新举报控制器
* */
import ContArrange from '@/admin/view/site/common/cont/contArrange';
import Page from '@/admin/view/site/common/page/page';
import tableNoList from '@/admin/view/site/common/table/tableNoList';
import webDb from 'webDbHelper';


export default {
  data:function() {
    return {
      searchData: {           // 搜索条件
        userName: '',         // 举报人用户名
        reportType: null,     // 举报类型（主题、评论、个人主页）
        reportTime: ['', ''], // 举报时间范围
        status: 0,            // 是否已处理 0 否 1 是
      },
      reportTypeData: [       // 举报类型数据
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
      reportListAll: [],      // 举报列表全选
      reportList: [],         // 举报列表数据
      submitForm:[],          // 操作表单
      pageData: {
        pageSize: 10,          // 每页显示数
        pageCount: 1,         // 总页数
        pageNumber: 1,        //当前页
        pageTotal: 0,         // 举报列表总条数
      },
      subLoading:false,       // 提交按钮状态
      visible: false,
    }
  },

  methods: {
    /*
    * 举报列表选择
    * */
   closeDelet(index) {
    this.$refs[index][0].doClose();
  },
    radioChange(event, index){
      switch (event){
        case 0:
          this.submitForm[index].type = 0;
          break;
        case 1:
          this.submitForm[index].type = 1;
          break;
      }
    },
    /*
    * 格式化日期
    * */
    formatDate(data){
      return this.$dayjs(data).format('YYYY-MM-DD HH:mm')
    },
    /*
    * 获取举报类型
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
        "filter[status]": 0,
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
        this.submitForm = [];
        this.reportList.forEach(item => {
          this.reportListAll.push(item._data.id);
          this.submitForm.push({
            radio: '',
            id: item._data.id,
            type: null
          })
        })
      }
     })
   },
  /*
  * 切换页码
  **/
  handleCurrentChange(num) {
    this.pageData.pageNumber = num;
    this.getReportList(num);
  },
  /*
  * 操作方式 delete删除(1单选 2全选) handle已处理(1单选 2全选)
  **/
  operationsSubmit(type, state, id){
    let submitData = [];
    const attributes = {
      "status": 1
    }
    if(type === 'delete'){
      switch (state){
        case 1:
          this.deleteOperation(id);
          break;
        case 2:
          this.deleteOperation(this.reportListAll.toString());
          break;
      }
    }else{
      switch (state){
        case 1:
          submitData = [{
            'type': 'report',
            'id': id,
            'attributes': attributes
          }]
          break;
        case 2:
          this.reportListAll.forEach(item => {
            submitData.push({
              'type': 'report',
              'id': item,
              'attributes': attributes
            })
          })
          break;
      }
      this.HandledOperation(submitData);
    }
  },
  /*
  * 删除请求
  **/
  deleteOperation(data) {
    let that = this;
    this.appFetch({
      url: 'reportsBatch',
      splice: '/' + data,
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
      that.getReportList(Number(webDb.getLItem('pageNumber')) || 1)
    },300)
   },
    /*
    * 已处理请求
    **/
    HandledOperation(submitData) {
      this.appFetch({
        url: 'reports',
        splice: '/batch',
        method: 'patch',
        data: { data: submitData }
      }).then(res => {
        if(res){
          this.subLoading = false;
          this.$message({
            message: '操作成功',
            type: 'success'
          });
          this.getReportList(Number(webDb.getLItem('pageNumber')) || 1)
        }  
      })
    },
    /*
    * 提交
    **/
    submitClick() {
      let result = this.submitForm.some(item=>{
        if(item.type === 0){
           return true;
        }
        if(item.type === 1){
          return true;
        }
      })
      if(!result){
        this.$message({
          showClose: true,
          message: '操作举报列表为空，请选择举报信息',
          type: 'warning'
        });
        return;
      }
      this.subLoading = true;
      let deleteList = [];
      let handledList = []
      const attributes = {
        "status": 1
      }
      this.submitForm.forEach(item =>{
        if (item.type === 0){
          deleteList.push(item.id);
        }
        if (item.type === 1){
          handledList.push({
            'type': 'report',
            'id': item.id,
            'attributes': attributes
          })
        }
      });
      if(deleteList.length > 0){
        this.deleteOperation(deleteList.join(','))
      }
      if(handledList.length > 0){
        this.HandledOperation(handledList);
      }
    },
    getCreated(state){
      if(state){
        this.getReportList(1);
      } else {
        this.getReportList(Number(webDb.getLItem('pageNumber')) || 1);
      }
    }
  },
 
  created(){
    this.getUrl();
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