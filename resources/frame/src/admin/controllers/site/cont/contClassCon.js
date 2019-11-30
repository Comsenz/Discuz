/*
* 内容分类控制器
* */

import Card from '../../../view/site/common/card/card';
import TableContAdd from '../../../view/site/common/table/tableContAdd';


export default {
  data:function () {
    return {
      tableData: [{
        className: '站长',
        sort: '1',
        classIntroduction:'分类介绍分类介绍分类介绍分类介绍分类介绍分类介绍'
      }, {
        className: '攻城狮',
        sort: '2',
        classIntroduction:'分类介绍分类介绍分类介绍分类介绍分类介绍分类介绍'
      }, {
        className: '版主',
        sort: '3',
        classIntroduction:'分类介绍分类介绍分类介绍分类介绍分类介绍分类介绍'
      }],

      deleteStatus:true,
      multipleSelection:[]
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
      console.log(123);
    }
  },

  created(){
    this.appFetch({
      url:'classify',
      method:'get',
      data:{}
    }).then(res => {
      console.log(res);
    })
  },

  components:{
    Card,
    TableContAdd
  }

}
