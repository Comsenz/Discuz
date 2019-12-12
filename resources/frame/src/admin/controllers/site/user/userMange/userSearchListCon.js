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
      pageNum: 1
    }
  },

  created(){
    this.handleGetUserList();
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

    async handleGetUserList(initStatus){
      try{
        const {
          username,
          userUID,
          userRole,
          userPhone,
          radio1,
        } = this.$route.query;
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

        console.log(response,'response')
      } catch(err){

      }
    },
  },
  components:{
    Card,
    CardRow
  }
}
