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
        label: '不处理'
      }, {
        value: '选项2',
        label: '处理'
      },{
        value: '选项3',
        label: '替换'
      }
    ],
      serachVal:'',
      checked:false,
      input:'',
      searchData :[],//搜索后的数据
      replace:true,
      radio2:"1",
      userLoadMoreStatus: true,
      userLoadMorePageChange: false,
      // loginStatus:'',  //default  batchSet
      deleteStatus:true,
      // contentParams: {
      //   'filter[p]': '',
      //   'page[number]': 1,
			// }

    }
  },
  created(){
    this.contentFilterList()  //初始化页面数据
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
    contentFilterList(){
      this.appFetch({
        url:'serachWords',
        method:'get',
        data:{}
      }).then(res=>{
        console.log(res)
        console.log(res.readdata[0]._data.find)
        this.tableData = res.readdata;
        console.log(this.tableData,'1111111111111111111')
      })
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
      if(initStatus){
        this.tableData = [];
      }
      try{
        await this.appFetch({
          url:'serachWords',
          method:'get',
          data:{
            'filter[q]':this.serachVal
          }
        }).then(res=>{
          this.tableData = this.tableData.concat(res);
        }).catch(err=>{

        })
      } finally {
        this.userLoadMorePageChange = false;
      }
    },

    handleLoadMoreUser(){
      this.userLoadMorePageChange = true;
      this.handleSearchUser();
    },
    
    loginStatus(){  //批量提交接口
      this.appFetch({
        url:'batchSubmit',
        method:'post',
        data:{
          "data": {
            "type": "stop-words",
            "words": [
                "2=2121222113111",
                "eqwe1e=adw",
                "123={MOD}|ds,",
                "MOD=111"
            ]
        }
        }
      }).then(res=>{
        console.log(res)
      })
    },
  
  },
  components:{
    Card,
    CardRow,
    TableContAdd
  }
}
