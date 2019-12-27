/*
* 回收站-回帖控制器
* */

import Card from '../../../../view/site/common/card/card';
import ContArrange from '../../../../view/site/common/cont/contArrange';
import Page from '../../../../view/site/common/page/page';
import tableNoList from '../../../../view/site/common/table/tableNoList';
import webDb from 'webDbHelper';
import moment from "moment/moment";

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
      releaseTime: ['',''],            //发布时间范围
      deleteTime: ['',''],             //删除时间范围

      radioList:'',               //主题左侧单选
      deleteStatusList:[],        //硬删除列表

      appleAll:false,             //应用其他页面
      themeList:[],               //主题列
      currentPaga: 1,             //当前页数
      total:0,                    //主题列表总条数
      pageCount:1,                //总页数
      submitForm:[],              //提交操作表单

    }
  },

  methods:{
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
          console.log("左侧操作错误，请刷新页面!")
      }
    },

    searchClick(){
      console.log(this.releaseTime);
      this.getPostsList(1);
    },

    handleCurrentChange(val){
      this.getPostsList(val);
    },

    submitClick() {
      console.log(this.submitForm);

      this.deleteStatusList = [];
      let deleteStr = '';
      let isDeleted = [];

      this.submitForm.forEach((item,index)=>{
        if (item.hardDelete){
          this.deleteStatusList.push(item.id);
        };
        if (!item.attributes.isDeleted){
          isDeleted.push(item.id)
        };
      });

      /*this.deleteStatusList.forEach((item,index)=>{
        if (index < this.deleteStatusList.length-1){
          deleteStr = deleteStr + item + ','
        }else {
          deleteStr = deleteStr + item
        }
      });*/

      if (this.deleteStatusList.length > 0){
        this.deleteThreadsBatch(deleteStr.join(','));
      }
      if (isDeleted.length > 0){
        this.patchPostsBatch(this.submitForm);
      }

    },

    allOperationsSubmit(val){
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
          this.deleteThreadsBatch(deleteStr);
          break;
        default:
          console.log("全部还原或全部删除操作错误,请刷新页面!")
      }
    },

    /*
    * 格式化日期
    * */
    formatDate(data){
      return moment(data).format('YYYY-MM-DD HH:mm')
    },


    /*
    * 接口请求
    * */
    getPostsList(pageNumber){
      this.appFetch({
        url:'posts',
        method:'get',
        data:{
          include: ['user','replyUser','thread','thread.category','thread.firstPost','deletedUser','lastDeletedLog'],
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
            Select:'无',
            radio:'',
            type:'posts',
            id:item._data.id,
            attributes: {
              isApproved: 0,
              isDeleted:true,
              message:'',
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
        url:'threads',
        method:'patch',
        splice:'/' + id,
        data:{
          data
        }
      }).then(res=>{
        if (res.meta && res.data){
          this.checkedTheme = [];
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
