/*
* 内容审核-回复审核控制器
* */

import Card from '../../../view/site/common/card/card';
import ContArrange from '../../../view/site/common/cont/contArrange';
import Page from '../../../view/site/common/page/page';
import tableNoList from '../../../view/site/common/table/tableNoList';
import webDb from 'webDbHelper';
import moment from "moment/moment";

export default {
  data:function () {
    return {
      searchUserName:'',  //用户名
      keyWords:'',        //关键词
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
      reasonForOperationSelect:1,   //操作理由选中
      appleAll:false,             //应用其他页面
      themeList:[],               //主题列表
      currentPaga: 1,             //当前页数
      total:0,                    //主题列表总条数
      pageCount:1,                //总页数
      ignoreStatus:true,         //全部忽略是否显示
      submitForm:[],            //操作理由表单

      //未审核0，已审核\通过1，已忽略2
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

    reasonForOperationChange(event,index){
      this.submitForm[index].attributes.message = event;
    },

    handleCurrentChange(val) {
      console.log(val);
      // webDb.setLItem('currentPag',val);
      // this.isIndeterminate = false;
      // this.checkAll = false;
      this.getPostsList(val);
    },

    postSearch(){
      this.ignoreStatus = this.searchReviewSelect === 2?false:true;
      this.currentPaga = 1;
      this.getPostsList();
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
      this.patchPostsBatch(this.submitForm);
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
      this.patchPostsBatch(this.submitForm);
    },

    singleOperationSubmit(val,categoryId,themeId,index){
      let data = {
        "type": "posts",
        "attributes": {
          "isApproved": 0,
          'isDeleted':false
        }
      };
      switch (val){
        case 1:
          data.attributes.isApproved = 1;
          this.patchPosts(data,themeId);
          break;
        case 2:
          data.attributes.isDeleted = true;
          data.attributes.message = this.submitForm[index].attributes.message;
          this.patchPosts(data,themeId);
          break;
        case 3:
          data.attributes.isApproved = 2;
          this.patchPosts(data,themeId);
          break;
        default:
          console.log("系统错误，请刷新页面");
      }
      console.log(data);
    },

    viewClick(id){
      //查看:/details/140  带id
      //编辑：/reply-to-topic  隐藏传入内容，带id
      //回帖：replyId

      let routeData = this.$router.resolve({
        path: "/details/" + id,   //id当前是回帖id
      });
      window.open(routeData.href, '_blank');
    },

    editClick(id,replyId){
      console.log(id);
      let routeData = this.$router.resolve({
        name: 'reply-to-topic',
        query: {
          themeId: id,
          replyId:replyId
        }
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
    getPostsList(pageNumber){
      this.appFetch({
        url:'posts',
        method:'get',
        data:{
          include: ['user','thread','thread.category','thread.firstPost'],
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
        this.themeList=[];
        this.submitForm = [];
        this.themeList = res.readdata;
        this.total = res.meta.postCount;
        this.pageCount = res.meta.pageCount;

        this.themeList.forEach((item,index)=>{
          this.submitForm.push({
            // message:'',
            Select:'无',
            radio:'',
            type:'posts',
            id:item._data.id,
            attributes: {
              isApproved: 0,
              isDeleted:false,
              message:''
            }
          })
        });

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
        this.categoriesList = [];
        res.data.forEach((item,index)=>{
          this.categoriesList.push({
            name:item.attributes.name,
            id:item.id
          })
        })
      }).catch(err=>{
        console.log(err);
      })

    },
    patchPostsBatch(data){
      this.appFetch({
        url:'postsBatch',
        method:'patch',
        data:{
          data
        }
      }).then(res=>{
        if (res.meta && res.data){
          this.$message.error('操作失败！');
        }else {
          this.getPostsList(Number(webDb.getLItem('currentPag'))||1);
          this.$message({
            message: '操作成功',
            type: 'success'
          });
        }
        console.log(res);
      }).catch(err=>{

      })
    },
    patchPosts(data,id){
      this.appFetch({
        url:'posts',
        method:'patch',
        splice:'/' + id,
        data:{
          data
        }
      }).then(res=>{
        if (res.meta && res.data){
          this.$message.error('操作失败！');
        }else {
          this.getPostsList(Number(webDb.getLItem('currentPag'))||1);
          this.$message({
            message: '操作成功',
            type: 'success'
          });
        }
      }).catch(err=>{
        console.log(err);
      })
    }

  },
  created(){
    this.getCategories();
    this.getPostsList(Number(webDb.getLItem('currentPag'))||1);
  },

  components:{
    Card,
    ContArrange,
    Page,
    tableNoList
  }

}
