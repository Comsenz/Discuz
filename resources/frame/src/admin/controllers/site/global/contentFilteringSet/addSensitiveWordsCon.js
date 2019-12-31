/*
* 批量添加敏感词
* */

import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';


export default {
  data:function () {
    return {
      tableData: [{
        name: '张三',
        method: '处理',
        address: '上海市普陀区金沙江路 1518 弄',
        value:'不处理'
      }, {
        name: '李四',
        method: '不处理',
        address: '上海市普陀区金沙江路 1518 弄',
        value:'处理'
      }, {
        name: '王五',
        method: '处理',
        address: '上海市普陀区金沙江路 1518 弄',
        value:'不处理'
      }, {
        name: '赵六',
        method: '不处理',
        address: '上海市普陀区金沙江路 1518 弄',
        value:'处理'
      }, {
        name: '田七',
        method: '处理',
        address: '上海市普陀区金沙江路 1518 弄',
        value:'不处理'
      }],
      multipleSelection: [],

      options: [{
        value: '选项1',
        label: '不处理'
      }, {
        value: '选项2',
        label: '处理'
      }],

      checked:false,
      radio2:false,
      // loginStatus:'default',  //default  batchSet
      deleteStatus:true,
      textarea:''

    }
  },
  methods:{
    toggleSelection(rows) {
      if (rows) {
        rows.forEach(row => {
          this.$refs.multipleTable.toggleRowSelection(row);
        });
      } else {
        this.$refs.multipleTable.clearSelection();
      }
    },
    handleSelectionChange(val) {
      this.multipleSelection = val;

      if (this.multipleSelection.length >= 1){
        this.deleteStatus = false
      } else {
        this.deleteStatus = true;
      }

    },
    loginStatus(){
       if(this.textarea.trim() === ''){
           return;
       }
      let wordsArr = this.textarea.split('\n')
         this.appFetch({
         url:'batchSubmit',
         method:'post',
         standard:false,
          data:{
           "data": 
                {
                  "type": "stop-words",
                  "words": wordsArr,
                  "overwrite":this.radio2 === 'true'?true:false
              }
              }
            }).then(res=>{
               if (res.errors){
                 this.$toast.fail(res.errors[0].code);
               }
            })
          },
  },
  components:{
    Card,
    CardRow
  }
}
