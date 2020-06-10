/*
* 回收站-回帖控制器
* */

import Card from '../../../../view/site/common/card/card';
import ContArrange from '../../../../view/site/common/cont/contArrange';
import Page from '../../../../view/site/common/page/page';
import tableNoList from '../../../../view/site/common/table/tableNoList';
import webDb from 'webDbHelper';
import ElImageViewer from 'element-ui/packages/image/src/image-viewer'
import commonHelper from "../../../../../helpers/commonHelper";


export default {
  data:function () {
    return {
      searchUserName:'',  //作者
      keyWords:'',        //关键词
      operator:'',        //操作人
      categoriesList:[],
      categoriesListSelect:'',    //搜索分类选中
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
      releaseTime: ['',''],       //发布时间范围
      deleteTime: ['',''],        //删除时间范围

      radioList:'',               //主题左侧单选
      deleteStatusList:[],        //硬删除列表

      appleAll:false,             //应用其他页面
      themeList:[],               //主题列
      currentPaga: 1,             //当前页数
      total:0,                    //主题列表总条数
      pageCount:1,                //总页数
      submitForm:[],              //提交操作表单
      showViewer:false,           //预览图
      url:[],
      subLoading:false,           //提交按钮状态
      btnLoading:0,               //0表示没有loading状态，1：全部还原、2：全部删除

    }
  },

  methods:{

    titleIcon(item){
      return commonHelper.titleIcon(item);
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

    radioChange(val,index){
      switch (val){
        case '还原':
          this.submitForm[index].attributes.isDeleted = false;
          this.submitForm[index].hardDelete = false;
          break;
        case '删除':
          this.submitForm[index].hardDelete = true;
          break;
        default:
      }
    },

    searchClick(){
      this.currentPaga = 1;
      this.getPostsList(1);
    },

    handleCurrentChange(val){
      document.getElementsByClassName('index-main-con__main')[0].scrollTop = 0;
      webDb.setLItem('currentPag',val);
      this.currentPaga = val;
      this.getPostsList(val);
    },

    submitClick() {
      this.subLoading = true;
      this.deleteStatusList = [];
      let isDeleted = [];

      this.submitForm.forEach((item,index)=>{
        if (item.hardDelete){
          this.deleteStatusList.push(item.id);
        }
        if (!item.attributes.isDeleted){
          isDeleted.push(item.id)
        }
      });

      if (this.deleteStatusList.length > 0){
        this.deletePostsBatch(this.deleteStatusList.join(','));
      }
      if (isDeleted.length > 0){
        this.patchPostsBatch(this.submitForm);
      }

    },

    allOperationsSubmit(val){
      this.btnLoading = val;
      let deleteStr = '';
      switch (val){
        case 1:
          this.submitForm.forEach((item,index)=>{
            this.submitForm[index].attributes.isDeleted = false;
          });
          this.patchPostsBatch(this.submitForm);
          break;
        case 2:
          this.submitForm.forEach((item,index)=>{
            if (index < this.submitForm.length-1){
              deleteStr = deleteStr + item.id + ','
            }else {
              deleteStr = deleteStr + item.id
            }
          });
          this.deletePostsBatch(deleteStr);
          break;
        default:
      }
    },

    /*
    * 格式化日期
    * */
    formatDate(data){
      return this.$dayjs(data).format('YYYY-MM-DD HH:mm')
    },


    /*
    * 接口请求
    * */
    getPostsList(pageNumber){
      this.appFetch({
        url:'posts',
        method:'get',
        data:{
          include: ['user','replyUser','thread','thread.category','thread.firstPost','deletedUser','lastDeletedLog','images'],
          'filter[isDeleted]':'yes',
          'filter[username]':this.searchUserName,
          'page[number]':pageNumber,
          'page[size]':10,
          'filter[q]':this.keyWords,
          'filter[categoryId]':this.categoriesListSelect,
          'filter[deletedUsername]':this.operator,
          'filter[createdAtBegin]':this.releaseTime[0],
          'filter[createdAtEnd]':this.releaseTime[1],
          'filter[deletedAtBegin]':this.deleteTime[0],
          'filter[deletedAtEnd]':this.deleteTime[1],
          'sort':'-deletedAt'
        }
      }).then(res=>{
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          this.themeList = [];
          this.submitForm = [];
          this.themeList = res.readdata;
          this.total = res.meta.postCount;
          this.pageCount = res.meta.pageCount;

          this.themeList.forEach((item, index) => {
            this.submitForm.push({
              Select: '无',
              radio: '',
              type: 'posts',
              id: item._data.id,
              attributes: {
                isDeleted: true,
                message: '',
              }
            })
          });
        }
      }).catch(err=>{
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
        this.subLoading = false;
        this.btnLoading = 0;
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          if (res.meta && res.data) {
            this.$message.error('操作失败！');
          } else {
            this.getPostsList(Number(webDb.getLItem('currentPag')) || 1);
            this.$message({
              message: '操作成功',
              type: 'success'
            });
          }
        }
      }).catch(err=>{

      })
    },
    patchPosts(data,id){
      this.appFetch({
        url:'threads',
        method:'patch',
        splice:'/' + id,
        data:{
          data
        }
      }).then(res=>{
        this.subLoading = false;
        this.btnLoading = 0;
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          if (res.meta && res.data) {
            this.checkedTheme = [];
            this.$message.error('操作失败！');
          } else {
            this.getPostsList(Number(webDb.getLItem('currentPag')) || 1);
            this.$message({
              message: '操作成功',
              type: 'success'
            });
          }
        }
      }).catch(err=>{
      })
    },

    deletePostsBatch(data){
      this.appFetch({
        url:'postBatch',
        method:'delete',
        splice:'/'+ data
      }).then(res=>{
        this.subLoading = false;
        this.btnLoading = 0;
        if (res.meta){
          res.meta.forEach((item,index)=>{
            setTimeout(()=>{
              this.$message.error(item.code)
            },(index+1) * 500);
          });
        }else {
          this.getPostsList(Number(webDb.getLItem('currentPag')) || 1);
          this.$message({
            message: '操作成功',
            type: 'success'
          });
        }
      }).catch(err=>{
      })
    },

    getCreated(state){
      if(state){
        this.getPostsList(1);
      } else {
        this.getPostsList(Number(webDb.getLItem('currentPag'))||1);
      }
    }

  },
  created(){
    this.getCategories();
    // this.getPostsList(Number(webDb.getLItem('currentPag'))||1);
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
    Card,
    ContArrange,
    Page,
    tableNoList,
    ElImageViewer
  }

}
