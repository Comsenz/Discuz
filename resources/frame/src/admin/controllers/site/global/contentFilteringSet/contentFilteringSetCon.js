/*
* 内容过滤设置
* */

import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';
import TableContAdd from '../../../../view/site/common/table/tableContAdd';


export default {
  data:function () {
    return {
      tableData: [
      //   {
      //   name: '张三',
      //   method: '处理',
      //   address: '上海市普陀区金沙江路 1518 弄',
      //   value:'不处理'
      // }, {
      //   name: '李四',
      //   method: '不处理',
      //   address: '上海市普陀区金沙江路 1518 弄',
      //   value:'处理'
      // }, {
      //   name: '王五',
      //   method: '处理',
      //   address: '上海市普陀区金沙江路 1518 弄',
      //   value:'不处理'
      // }, {
      //   name: '赵六',
      //   method: '不处理',
      //   address: '上海市普陀区金沙江路 1518 弄',
      //   value:'处理'
      // }, {
      //   name: '田七',
      //   method: '处理',
      //   address: '上海市普陀区金沙江路 1518 弄',
      //   value:'不处理'
      // }
    ],
      multipleSelection: [],

      options: [{
        value: '选项1',
        label: '审核'
      }, {
        value: '选项2',
        label: '禁用'
      },{
        value: '选项3',
        label: '替换'
      }
    ],
      serachVal:'',
      checked:false,
      searchData :[],//搜索后的数据
      replace:true,
      radio2:"1",
      userLoadMoreStatus: true,
      userLoadMorePageChange: false,
      deleteStatus:true,
    }
  },
  created(){
    this.handleSearchUser(true);  //初始化页面数据
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
      console.log(this.multipleSelection,'this.multipleSelection')
      if (this.multipleSelection.length >= 1){
        this.deleteStatus = false
      } else {
        this.deleteStatus = true;
      }

    },

    onSearch(val) {
      this.searchVal = val;
      // console.log(val,'value')
      // this.contentParams = {
      //   'filter[q]': this.searchVal,
      // }
      console.log(this.serachVal,'5555555555555555')
      this.handleSearchUser(true);

    },
    async handleSearchUser(initStatus = false){
      
      try{
        const response = await this.appFetch({
          url:'serachWords',
          method:'get',
          data:{
            'filter[q]':this.serachVal
          }
        })
        if(initStatus){
          this.tableData = [];
        }
        this.tableData = this.tableData.concat(response.readdata).map((v)=>{
          if(v._data.inputVal === undefined){
            v._data.inputVal = '';
          }
          return v;
        });
        console.log(this.tableData)
      } catch(err){

      } finally {
        this.userLoadMorePageChange = false;
      }
    },

    handleLoadMoreUser(){
      this.userLoadMorePageChange = true;
      this.handleSearchUser();
    },
    
    async loginStatus(){  //批量提交接口

      try{
        if(this.multipleSelection.length === 0){
          return;
        }

        let words = [];

        this.multipleSelection.forEach((v,i)=>{
          const _data = v._data;
          words.push(`${_data.find}=${_data.inputVal}`)
        })

        await this.appFetch({
          url:'batchSubmit',
          method:'post',
          data:{
            "data": {
              "type": "stop-words",
              "words": words
          }
          }
        })

      } catch(err){
        console.error(err,'function loginStatus error')
      }
      
    },
  
  },
  components:{
    Card,
    CardRow,
    TableContAdd
  }
}
