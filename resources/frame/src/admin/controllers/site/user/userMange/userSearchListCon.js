/*
* 用户搜索结果
* */

import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';


export default {
  data:function () {
    return {
      tableData: [{
        number: '00220',
        userName: '王小虎',
        publishTopic: '科技',
        userGroup:'管理员'
      }, {
        number: '00220',
        userName: '王小虎',
        publishTopic: '网络',
        userGroup:'成员'
      }, {
        number: '00220',
        userName: '王小虎',
        publishTopic: '禁用',
        userGroup:'管理员'
      }, {
        number: '00220',
        userName: '王小虎',
        publishTopic: '科技',
        userGroup:'管理员'
      }],

      multipleSelection:[],

      deleteStatus:true
    }
  },
  methods:{
    handleSelectionChange(val) {
      this.multipleSelection = val;

      if (this.multipleSelection.length >= 1){
        this.deleteStatus = false
      } else {
        this.deleteStatus = true;
      }

    }
  },
  components:{
    Card,
    CardRow
  }
}
