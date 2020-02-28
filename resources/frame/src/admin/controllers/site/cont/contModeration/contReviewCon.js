/*
* 内容审核-主题审核控制器
* */

import Card from '../../../../view/site/common/card/card';
import ContArrange from '../../../../view/site/common/cont/contArrange';
import Page from '../../../../view/site/common/page/page';
import tableNoList from '../../../../view/site/common/table/tableNoList';
import webDb from 'webDbHelper';
import moment from "moment/moment";
import ElImageViewer from 'element-ui/packages/image/src/image-viewer'


export default {
  data:function () {
    return {
      searchUserName:'',          //用户名
      keyWords:'',                //关键词
      showSensitiveWords:false,   //显示敏感词
      pageOptions: [
        {
          value: 10,
          label: '每页显示10条'
        }, {
          value: 20,
          label: '每页显示20条'
        }, {
          value: 30,
          label: '每页显示30条'
        }
      ],
      pageSelect:10,              //每页显示数选择值选中
      searchReview:[
        {
          value:0,
          label:'未审核'
        },
        {
          value:2,
          label:'已忽略'
        }
      ],
      searchReviewSelect:0,       //审核状态选中
      categoriesList:[],
      categoriesListSelect:'',    //搜索分类选中
      searchTime:[
        {
          value:1,
          label:'全部'
        },
        {
          value:2,
          label:'最近一周'
        },
        {
          value:3,
          label:'最近一个月'
        },
        {
          value:4,
          label:'最近三个月'
        }
      ],
      searchTimeSelect:1,         //搜索时间选中
      relativeTime:['',''],       //搜索相对时间转换

      submitForm:[],              //操作理由表单
      reasonForOperation:[
        {
          value:'无',
          label:'无'
        },
        {
          value:'广告/SPAM',
          label:'广告/SPAM'
        },
        {
          value:'恶意灌水',
          label:'恶意灌水'
        },
        {
          value:'违规内容',
          label:'违规内容'
        },
        {
          value:'文不对题',
          label:'文不对题'
        },
        {
          value:'重复发帖',
          label:'重复发帖'
        },
        {
          value:'我很赞同',
          label:'我很赞同'
        },
        {
          value:'精品文章',
          label:'精品文章'
        },
        {
          value:'原创内容',
          label:'原创内容'
        },
        {
          value:'其他',
          label:'其他'
        }
      ],
      reasonForOperationSelect:1, //操作理由选中
      appleAll:false,             //应用其他页面
      themeList:[],               //主题列表
      currentPaga: 1,             //当前页数
      total:0,                    //主题列表总条数
      pageCount:1,                //总页数
      ignoreStatus:true,          //全部忽略是否显示
      showViewer:false,      //预览图
      url:[]

      //未审核0，已审核\通过1，已忽略2
    }
  },

  methods:{
    imgShowClick(list,imgIndex){
      console.log(list);
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

    reasonForOperationChange(event,index){
      this.submitForm[index].attributes.message = event;
      console.log(this.submitForm[index]);
    },

    handleCurrentChange(val) {
      document.getElementsByClassName('index-main-con__main')[0].scrollTop = 0;
      this.isIndeterminate = false;
      this.checkAll = false;
      this.currentPaga = val;
      this.getThemeList(val);
    },

    themeSearch(){
      this.ignoreStatus = this.searchReviewSelect === 2?false:true;
      this.currentPaga = 1;
      this.getThemeList();
    },

    searchTimeChange(val){
      let end = new Date();
      let start = new Date();
      this.relativeTime = [];

      switch (val){
        case 1:
          this.relativeTime.push('','');
          break;
        case 2:
          start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
          this.relativeTime.push(this.formatDate(end),this.formatDate(start));
          break;
        case 3:
          start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
          this.relativeTime.push(this.formatDate(end),this.formatDate(start));
          break;
        case 4:
          start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
          this.relativeTime.push(this.formatDate(end),this.formatDate(start));
          break;
        default:
          this.$message.error('搜索日期选择错误，请重新选择！或 刷新页面（F5）');
      }

      console.log('相对时间：'+this.relativeTime);

    },

    submitClick() {
      console.log(this.submitForm);
      this.patchThreadsBatch(this.submitForm);
    },

    radioChange(event,index){
      switch (event){
        case 0:
          this.submitForm[index].attributes.isApproved = 1;
          break;
        case 1:
          this.submitForm[index].attributes.isDeleted = true;
          break;
        case 2:
          this.submitForm[index].attributes.isApproved = 2;
          break;
      }
    },

    allOperationsSubmit(val){
      switch (val){
        case 1:
          this.submitForm.forEach((item,index)=>{
              this.submitForm[index].attributes.isApproved = 1;
          });
          break;
        case 2:
          this.submitForm.forEach((item,index)=>{
            this.submitForm[index].attributes.isDeleted = true;
          });
          break;
        case 3:
          this.submitForm.forEach((item,index)=>{
            this.submitForm[index].attributes.isApproved = 2;
          });
          break;
      }
      this.patchThreadsBatch(this.submitForm);
    },

    singleOperationSubmit(val,categoryId,themeId,index){
      let data = {
        "type": "threads",
        "attributes": {
          "isApproved": 0,
          'isDeleted':false
        },
        "relationships": {
          "category": {
            "data": {
              "type": "categories",
              "id": categoryId
            }
          }
        }
      };
      switch (val){
        case 1:
          data.attributes.isApproved = 1;
          this.patchThreads(data,themeId);
          break;
        case 2:
          data.attributes.isDeleted = true;
          data.attributes.message = this.submitForm[index].attributes.message;
          this.patchThreads(data,themeId);
          break;
        case 3:
          data.attributes.isApproved = 2;
          this.patchThreads(data,themeId);
          break;
        default:
          console.log("系统错误，请刷新页面");
      }
    },

    viewClick(id){
      //查看:/details/140  带id
      //编辑：/reply-to-topic  隐藏传入内容，带id
      //回帖：replyId

      let routeData = this.$router.resolve({
        path: "/details/" + id,
      });
      window.open(routeData.href, '_blank');
    },

    editClick(id){
      console.log(id);
      let routeData = this.$router.resolve({
        path: `/edit-topic/${id}`
      });
      window.open(routeData.href, '_blank');
    },

    /*
    * 格式化日期
    * */
    formatDate(data){
      return moment(data).format('YYYY-MM-DD HH:mm')
    },

    /*
    * 请求接口
    * */
    getThemeList(pageNumber){
      this.appFetch({
        url:'threads',
        method:'get',
        data:{
          include:['user', 'firstPost', 'lastPostedUser', 'category','firstPost.images','firstPost.attachments'],
          'filter[isDeleted]':'no',
          'filter[username]':this.searchUserName,
          'page[number]':pageNumber,
          'page[size]':this.pageSelect,
          'filter[q]':this.keyWords,
          'filter[isApproved]':this.searchReviewSelect,
          'filter[createdAtBegin]':this.relativeTime[1],
          'filter[createdAtEnd]':this.relativeTime[0],
          'filter[categoryId]':this.categoriesListSelect,
          'filter[highlight]':this.showSensitiveWords?'yes':'no',
          'sort':'-updatedAt'
        }
      }).then(res=>{
        console.log(res);
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          this.themeList = [];
          this.submitForm = [];
          this.themeList = res.readdata;
          this.total = res.meta.threadCount;
          this.pageCount = res.meta.pageCount;

          this.themeList.forEach((item, index) => {
            this.submitForm.push({
              Select: '无',
              radio: '',
              type: 'threads',
              id: item._data.id,
              attributes: {
                isApproved: 0,
                isDeleted: false,
                message: '',
              },
              relationships: {
                category: {
                  data: {
                    type: "categories",
                    id: item.category._data.id
                  }
                }
              }
            })
          });
        }
      }).catch(err=>{
        console.log(err);
      })

    },
    getCategories(){
      this.appFetch({
        url:'categories',
        method:'get',
        data:{}
      }).then(res=>{
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          this.categoriesList = [];
          res.data.forEach((item, index) => {
            this.categoriesList.push({
              name: item.attributes.name,
              id: item.id
            })
          })
        }
      }).catch(err=>{
        console.log(err);
      })

    },
    patchThreadsBatch(data){
      this.appFetch({
        url:'threadsBatch',
        method:'patch',
        data:{
          data
        }
      }).then(res=>{
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          if (res.meta && res.data) {
            this.$message.error('操作失败！');
          } else {
            this.getThemeList(Number(webDb.getLItem('currentPag')) || 1);
            this.$message({
              message: '操作成功',
              type: 'success'
            });
          }
        }
        console.log(res);
      }).catch(err=>{

      })
    },
    patchThreads(data,id){
      this.appFetch({
        url:'threads',
        method:'patch',
        splice:'/' + id,
        data:{
          data
        }
      }).then(res=>{
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          if (res.meta && res.data) {
            this.checkedTheme = [];
            this.$message.error('操作失败！');
          } else {
            this.getThemeList(Number(webDb.getLItem('currentPag')) || 1);
            this.$message({
              message: '操作成功',
              type: 'success'
            });
          }
        }
      }).catch(err=>{
        console.log(err);
      })
    },

    getCreated(state){
      if(state){
        console.log(state);
        this.getThemeList(1);
      } else {
        console.log(state);
        this.getThemeList(Number(webDb.getLItem('currentPag'))||1);
      }
    }

  },

  created(){
    this.getCategories();
  },

  beforeRouteEnter(to,from,next){
    next(vm => {
      if (to.name !== from.name && from.name !== null){
        console.log('执行');
        vm.getCreated(true)
      }else {
        console.log('不执行');
        vm.getCreated(false)
      }
    })
  },

  components:{
    Card,
    ContArrange,
    Page,
    tableNoList,
    ElImageViewer
  }

}
