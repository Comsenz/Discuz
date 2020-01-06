/*
* 内容分类控制器
* */

import Card from '../../../view/site/common/card/card';
import TableContAdd from '../../../view/site/common/table/tableContAdd';

export default {
  data:function () {
    return {
      categoriesList: [],         //分类列表
      categoriesListLength:'',    //分类列表长度

      createCategoriesStatus:false,   //添加分类状态

      deleteStatus:true,
      multipleSelection:[],        //分类多选列表
      visible:false
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

    tableContAdd(){

      this.createCategoriesStatus = true;
      this.categoriesList.push({
        name:"",
        id:"",
        description:"",
        sort:""
      })

      /*if (this.categoriesList.length <= this.categoriesListLength){
        this.createCategoriesStatus = true;
        this.categoriesList.push({
          name:"",
          id:"",
          description:"",
          sort:""
        })
      }*/
    },

    submitClick(){

      if (this.createCategoriesStatus && this.multipleSelection.length > 0){
        this.$message({
          showClose: true,
          message: '新增内容分类未提交！请先提交，再勾选其他分类',
          type: 'warning'
        });
      } else if (this.createCategoriesStatus){
        console.log(this.categoriesList.slice(this.categoriesListLength, this.categoriesList.length));
        this.createCategories(this.categoriesList.slice(this.categoriesListLength,this.categoriesList.length)).then(()=>{
          this.getCategories();
          this.createCategoriesStatus = false;
        })
      } else if (this.multipleSelection.length > 0){
        let data = [];
        this.multipleSelection.forEach((item)=>{
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
      } else {
        this.$message({
          showClose: true,
          message: '操作选项错误，请重新选择 或 刷新页面(F5)',
          type: 'warning'
        });
      }

    },

    deleteClick(id,index){
      console.log(index);
      console.log(this.categoriesListLength);

      if (this.createCategoriesStatus && index > this.categoriesListLength -1){
        this.categoriesList.splice(index,1);
        console.log(this.categoriesList);
      } else {
        this.deleteCategories(id).then(()=>{
          this.getCategories();
        });
      }
    },

    deleteAllClick(){
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
        console.log(err);
      })

    },
    deleteCategories(id){
      return this.appFetch({
        url:'categoriesDelete',
        method:'delete',
        splice:'/'+id
      }).then(res=>{
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
        console.log(err);
      })
    },
    batchDeleteCategories(id){
      return this.appFetch({
        url:'categoriesBatchDelete',
        method:'delete',
        splice:'/'+id
      }).then(res=>{
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
        console.log(err);
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
                url:'createBatchCategories',
                method:'post',
                data:{
                  "data": datas
                }
              }).then(res=>{
                if (res.meta){
                  console.log(res.meta);
                  // if (res.errors[0].detail){
                  //   this.$message.error(res.errors[0].code + '\n' + res.errors[0].detail[0])
                  // } else {
                  //   this.$message.error(res.errors[0].code);
                  // }
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
                console.log(err);
              })
    },
    batchUpdateCategories(data){
      return  this.appFetch({
              url:'categoriesBatchUpdate',
              method:'patch',
              data:{
                  data
              }
            }).then(res=>{
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
