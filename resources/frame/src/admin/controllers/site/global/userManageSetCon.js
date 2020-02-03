/*
* 后台用户管理
* */

import Card from '../../../view/site/common/card/card';
import TableContAdd from '../../../view/site/common/table/tableContAdd';


export default {
  data:function () {
    return {
      checked:true,
      checkList:[],
      deleteStatus:true,
      tableData: [{
        name: '奶罩',
        role: '站长',
        value:'不处理'
      }, {
        name: '小虫',
        role: '版主',
        value:'处理'
      }, {
        name: '王五',
        role: '不处理',
        value:'不处理'
      }],
      options: [{
        value: '选项1',
        label: '不处理'
      }, {
        value: '选项2',
        label: '处理'
      }],
      multipleSelection:[]
    }
  },
  methods:{
    checkboxT(row,index){
      if (index === 0){
        return 0;
      }else {
        return 1;
      }
    },

    handleSelectionChange(val) {
      this.multipleSelection = val;

      if (this.multipleSelection.length >= 1){
        this.deleteStatus = false
      } else {
        this.deleteStatus = true
      }

    }

  },
  components:{
    Card,
    TableContAdd
  }
}
