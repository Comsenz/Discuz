/*
* 回收站-主题控制器
* */

import Card from '../../../../view/site/common/card/card';
import ContArrange from '../../../../view/site/common/cont/contArrange';
import Page from '../../../../view/site/common/page/page';
import tableNoList from '../../../../view/site/common/table/tableNoList';
import webDb from 'webDbHelper';
import ElImageViewer from 'element-ui/packages/image/src/image-viewer'


export default {
  data:function () {
    return {
      searchUserName:'',          //作者
      keyWords:'',                //关键词
      operator:'',                //操作人
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
      visible: false,
    }
  },

  methods:{
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
          //左侧操作错误，请刷新页面
      }
    },

    searchClick(){
      this.currentPaga = 1;
      this.getThemeList(1);
    },

    handleCurrentChange(val){
      document.getElementsByClassName('index-main-con__main')[0].scrollTop = 0;
      webDb.setLItem('currentPag',val);
      this.currentPaga = val;
      this.getThemeList(val);
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

      // this.deleteStatusList.forEach((item,index)=>{
      //   if (index < this.deleteStatusList.length-1){
      //     deleteStr = deleteStr + item + ','
      //   }else {
      //     deleteStr = deleteStr + item
      //   }
      // });

      if (this.deleteStatusList.length > 0){
        this.deleteThreadsBatch(this.deleteStatusList.join(','));
      }
      if (isDeleted.length > 0){
        this.patchThreadsBatch(this.submitForm);
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
          this.patchThreadsBatch(this.submitForm);
          break;
        case 2:
          this.submitForm.forEach((item,index)=>{
            if (index < this.submitForm.length-1){
              deleteStr = deleteStr + item.id + ','
            }else {
              deleteStr = deleteStr + item.id
            }
          });
          this.deleteThreadsBatch(deleteStr);
          break;
        default:
          //全部还原或全部删除操作错误,请刷新页面
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
    getThemeList(pageNumber){
      this.releaseTime = this.releaseTime == null?['','']:this.releaseTime;
      this.radioList = this.radioList == null?['','']:this.radioList;

      this.appFetch({
        url:'threads',
        method:'get',
        data:{
          include: ['user','firstPost','category','deletedUser','lastDeletedLog','firstPost.images','firstPost.attachments', 'threadVideo'],
          // include:['user', 'firstPost', 'lastPostedUser','deletedUser', 'category','firstPost.images','firstPost.attachments'],
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
          this.themeList = res.readdata;
          this.total = res.meta.threadCount;
          this.pageCount = res.meta.pageCount;
          this.submitForm = [];
          this.themeList.forEach((item, index) => {
            this.submitForm.push({
              radio: '',
              hardDelete: false,
              type: 'threads',
              id: item._data.id,
              attributes: {
                isDeleted: true
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
    patchThreadsBatch(data){
      this.appFetch({
        url:'threadsBatch',
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
            this.getThemeList(Number(webDb.getLItem('currentPag')) || 1);
            this.$message({
              message: '操作成功',
              type: 'success'
            });
          }
        }
      }).catch(err=>{

      })
    },
    deleteThreadsBatch(data){
      this.appFetch({
        url:'threadsBatch',
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
          this.getThemeList(Number(webDb.getLItem('currentPag')) || 1);
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
        this.getThemeList(1);
      } else {
        this.getThemeList(Number(webDb.getLItem('currentPag'))||1);
      }
    }

  },
  created(){
    this.getCategories();
  },

  beforeRouteEnter (to,from,next){
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
