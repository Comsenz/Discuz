/*
* 内容分类控制器
* */

import Card from '../../../view/site/common/card/card';
import TableContAdd from '../../../view/site/common/table/tableContAdd';

export default {
  data:function () {
    return {
      categoriesList: [],           //分类列表
      categoriesListLength:'',      //分类列表长度
      createCategoriesStatus:false, //添加分类状态
      deleteStatus:true,
      multipleSelection:[],         //分类多选列表
      visible:false,
      delLoading:false,             //删除按钮状态
      subLoading:false,             //提交按钮状态
      showClass:false,              //分类权限显示隐藏
      dialogVisible: false
    };
  },

  methods:{
    addClick() {
      console.log('12344');
    },
    handleSelectionChange(val) {
      this.multipleSelection = val;

      if (this.multipleSelection.length >= 1){
        this.deleteStatus = false
      } else {
        this.deleteStatus = true
      }

    },

    tableContAdd(){
      this.showClass = false;
      this.createCategoriesStatus = true;
      this.categoriesList.push({
        name:"",
        id:"",
        description:"",
        sort:""
      })
    },

    submitClick(){     //提交
      this.subLoading = true;
      this.showClass = true;

      /*if (this.createCategoriesStatus && this.multipleSelection.length > 0){
        this.$message({
          showClose: true,
          message: '新增内容分类未提交！请先提交，再勾选其他分类',
          type: 'warning'
        });
      } else */

      if (this.createCategoriesStatus){
        this.createCategories(this.categoriesList.slice(this.categoriesListLength,this.categoriesList.length)).then(()=>{
          this.getCategories();
          this.createCategoriesStatus = false;
        })
      } else {
        let data = [];
        this.categoriesList.forEach((item)=>{
          data.push({
            'type':"categories",
            'id':item.id,
            "attributes": {
              "name": item.name,
              "description": item.description,
              "sort": item.sort,
            }
          })
        });
        this.batchUpdateCategories(data).then(()=>{
          this.getCategories();
        });
      }
    },

    deleteClick(id,index){

      if (this.createCategoriesStatus && index > this.categoriesListLength -1){
        this.categoriesList.splice(index,1);
      } else {
        this.deleteCategories(id).then(()=>{
          this.getCategories();
        });
      }
    },

    deleteAllClick(){
      this.delLoading = true;
      let id = [];
      this.multipleSelection.forEach((item,index)=>{
        if (index < this.multipleSelection.length){
          id.push(item.id)
        }
      });

      this.batchDeleteCategories(id.join(',')).then(()=>{
        this.getCategories();
      });
      this.visible = false;
    },

    /*
    * 接口请求
    * */
    getCategories(){
      this.appFetch({
        url:'categories',
        method:'get',
        data:{}
      }).then(res=>{
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          this.categoriesListLength = res.data.length;
          this.categoriesList = [];
          res.data.forEach((item, index) => {
            this.categoriesList.push({
              name: item.attributes.name,
              id: item.id,
              description: item.attributes.description,
              sort: item.attributes.sort
            })
          })
        }
      }).catch(err=>{
      })

    },
    deleteCategories(id){
      return this.appFetch({
        url:'categoriesDelete',
        method:'delete',
        splice:'/'+id
      }).then(res=>{
        this.subLoading = false;
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          if (!res.meta) {
            this.$message({
              message: '操作成功',
              type: 'success'
            });
          } else {
            this.$message.error('操作失败！');
          }
        }
      }).catch(err=>{
      })
    },
    batchDeleteCategories(id){
      return this.appFetch({
        url:'categoriesBatchDelete',
        method:'delete',
        splice:'/'+id
      }).then(res=>{
        this.delLoading = false;
        if (res.meta){
          res.meta.forEach((item,index)=>{
            setTimeout(()=>{
              this.$message.error(item.code)
            },(index+1) * 500);
          });
        }else {
          this.$message({
            message: '操作成功',
            type: 'success'
          });
        }
      }).catch(err=>{
      })
    },
    createCategories(data){
      let datas = [];
      data.forEach((item)=>{
        datas.push({
          "type": "categories",
          "attributes": {
            "name": item.name,
            "description": item.description,
            "sort": item.sort
          }
        },)
      });

      return  this.appFetch({
                url:'createBatchCategories',     //批量创建分类
                method:'post',
                data:{
                  "data": datas
                }
              }).then(res=>{
                this.subLoading = false;
                if (res.meta){
                  res.meta.forEach((item,index)=>{
                    setTimeout(()=>{
                      this.$message.error(item.message.name[0])
                    },(index+1) * 500);
                  });
                }else {
                  this.$message({
                    message: '操作成功',
                    type: 'success'
                  });
                }
              }).catch(err=>{
              })
    },
    batchUpdateCategories(data) {
      return this.appFetch({
        url: 'categoriesBatchUpdate',      //批量修改分类
        method: 'patch',
        data: {
          data
        }
      }).then(res => {
        this.subLoading = false;
        if (res.meta) {
          // TODO 优化提示
          let errors = {
            'permission_denied': '权限不足！',
          }
          res.meta.forEach((item, index) => {
            setTimeout(() => {
              if (typeof item.message === 'string') {
                this.$message.error(errors[item.message] ? errors[item.message] : item.message)
              } else {
                this.$message.error(item.message.name[0])
              }
            }, (index + 1) * 500);
          });
        } else {
          this.$message({
            message: '操作成功',
            type: 'success'
          });
        }
      }).catch(err => {
        console.log(err);
      })
    }
  },

  created(){
    this.getCategories();
  },

  components:{
    Card,
    TableContAdd
  }

}
