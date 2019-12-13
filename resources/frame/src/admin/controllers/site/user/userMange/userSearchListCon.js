/*
* 用户搜索结果
* */

import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';


export default {
  data:function () {
    return {
      tableData: [],
      getRoleNameById: {},
      multipleSelection:[],

      deleteStatus:true,
      pageLimit: 20,
      pageNum: 1,
      query: {},
    }
  },

  created(){
    this.query = this.$route.query;
    this.handleGetUserList();
  },
  methods:{
    handleSelectionChange(val) {
      this.multipleSelection = val;
      console.log(this.multipleSelection)
      if (this.multipleSelection.length >= 1){
        this.deleteStatus = false
      } else {
        this.deleteStatus = true;
      }

    },

    async handleGetUserList(initStatus = false){
      try{
        const {
          username,
          userUID,
          userRole,
          userPhone,
          radio1,
        } = this.query;
        const response = await this.appFetch({
          method: "get",
          url: 'users',
          data: {
            "filter[username]": username,
            "filter[id]": userUID,
            "filter[group_id]": userRole,
            "filter[bind]": radio1 === '1' ? 'wechat':'',
            "page[limit]": this.pageLimit,
            "page[number]": this.pageNum
          }
        })

        if(initStatus){
          this.tableData = [];
        }
        this.tableData = this.tableData.concat(response.readdata);
        console.log(response,'response')
      } catch(err){

      }
    },

    handleReGetList(){
      this.handleGetUserList(true);
    },

    async exporUserInfo(){
      try{
        const response = await this.appFetch({
          method: 'get',
          url: 'exportUser',
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

        this.handleGetUserList(true);
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
        console.log(this.multipleSelection,'multipleSelection')
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

        this.handleGetUserList(true);
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
  },
  components:{
    Card,
    CardRow
  }
}
