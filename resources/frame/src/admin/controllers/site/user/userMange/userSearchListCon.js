/*
* 用户搜索结果
* */

import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';
import Page from '../../../../view/site/common/page/page';
import webDb from 'webDbHelper';


export default {
  data:function () {
    return {
      tableData: [],
      getRoleNameById: {},
      multipleSelection:[],

      deleteStatus:true,
      pageLimit: 15,
      pageNum: 1,
      query: {},
      total: 0,
    }
  },

  created(){
    this.query = this.$route.query;
    this.handleGetUserList();
    this.pageNum  = Number(webDb.getLItem('currentPag'))||1;
    this.handleGetUserList(Number(webDb.getLItem('currentPag'))||1);
  },
  methods:{
    handleSelectionChange(val) {
      this.multipleSelection = val;
      if (this.multipleSelection.length >= 1){
        this.deleteStatus = false
      } else {
        this.deleteStatus = true;
      }

    },

    async handleGetUserList(){
      try{
        const {
          username,
          userUID,
          userRole,
          userPhone,
          userStatus,
          radio1,
        } = this.query;
        const response = await this.appFetch({
          method: "get",
          url: 'users',
          data: {
            "filter[username]": username,
            "filter[id]": userUID,
            "filter[group_id]": userRole,
            "filter[mobile]": userPhone, 
            "filter[status]":userStatus,
            "filter[bind]": radio1 === '1' ? 'wechat':'',
            "page[limit]": this.pageLimit,
            "page[number]": this.pageNum,
            
          }
        })
        console.log(response)
        this.total = response.meta.total;
        this.pageNum = response.meta.pageCount;
        this.total = response.meta ? response.meta.total : 0;
        this.tableData = response.readdata;
      } catch(err){

      }
    },

    // handleCurrentChange(val){
    //   this.pageNum = val;
    //   webDb.setLItem('currentPag',val);
    //   this.handleGetUserList();
    // },

    async exporUserInfo(){
      try{
        let usersIdList = [];
        this.multipleSelection.forEach((v)=>{
          usersIdList.push(v._data.id)
        })
        const response = await this.appFetch({
          method: 'get',
          url: 'exportUser',
          splice:'ids'+'='+usersIdList,
          responseType: 'arraybuffer'
        })

        const blob = new Blob( [response], {type: 'application/x-xls'} )
        const url = window.URL || window.webkitURL || window.moxURL
        const downloadHref = url.createObjectURL(blob)
        let a = document.createElement('a');
        a.href = downloadHref,
        a.download = 'export.xlsx';
        a.click();
        a = null;
      } catch(err){
        console.error(err, 'exporUserInfo')
      }
    },

    async deleteBatch(){
      if(this.multipleSelection.length <= 0){
        return;
      }
      try{
        let userIdList = [];
        this.multipleSelection.forEach((v)=>{
          userIdList.push(v._data.id)
        })

        await this.appFetch({
          method: 'delete',
          url: 'users',
          data: {
            "data": {
              "attributes": {
                "id": userIdList
              }
            }
          }
        })

        this.handleGetUserList();
      } catch(err){
        console.error(err,'deleteBatch');
      }
    },

    async disabledBatch(){
      if(this.multipleSelection.length <= 0){
        return;
      }
      try{
        let dataList = [];
        this.multipleSelection.forEach((v)=>{

          dataList.push({
            "attributes": {
              "id": v._data.id,
              "groupId": v.groups[0] ? v.groups[0]._data.id : '',
              "status": 1
            }
          })
        })

        await this.appFetch({
          method: 'PATCH',
          url: 'users',
          data: {
            "data": dataList
          }
        })

        this.handleGetUserList();
      } catch(err){
        console.error(err, 'disabledBatch');
      }
    },

    async handleDisable(scope){

      try{
        const data = scope.row._data
        await this.appFetch({
          method: "PATCH",
          url: 'users',
          splice: `/${data.id}`,
          data: {
            "data": {
              "attributes": {
                "status": 1
              }
            } 
          }
        })

        this.tableData[scope.$index]._data.status = 1;
      } catch(err){
        console.error(err, 'handleDisable');
      }
    },
    handleCurrentChange(val){
      this.pageNum = val
      this.handleGetUserList();
    }
  },
  components:{
    Card,
    CardRow,
    Page
  }
}
