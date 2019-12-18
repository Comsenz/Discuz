/*
* 内容过滤设置
* */

import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';
import TableContAdd from '../../../../view/site/common/table/tableContAdd';
import Page from '../../../../view/site/common/page/page';

export default {
  data:function () {
    return {
      tableData: [],
      multipleSelection: [],
      tableDataLength:'',
      createCategoriesStatus:false,   //添加分类状态
      total:0,

      options: [{
          value: '{MOD}',
          label: '{MOD}'
        }, {
          value: '{BANNED}',
          label: '{BANNED}'
        },{
          value: '{REPLACE}',
          label: '{REPLACE}'
        }
      ],
      serachVal:'',
      checked:false,
      searchData :[],//搜索后的数据
      replace:true,
      inputFind:false,
      radio2:"1",
      pageLimit: 15,
      pageNum: 1,
      userLoadMoreStatus: true,
      userLoadMorePageChange: false,
      // loginStatus:'',  //default  batchSet
      deleteStatus:true,
      // contentParams: {
      //   'filter[p]': '',
      //   'page[number]': 1,
      // }
      
      deleteList:[]

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
            'filter[q]':this.serachVal,
            "page[limit]": this.pageLimit,
            "page[number]": this.pageNum
          }
        })
        if(initStatus){
          this.tableData = [];
        }
        
        this.tableData = this.tableData.concat(response.readdata).map((v)=>{
          if(v._data.inputVal === undefined){
            v._data.inputVal = '';
          }
          console.log(response)
          this.total = response.meta ? response.meta.stopWordCount : 0;
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

    selectChange(scope){
      console.log(scope,'scope');
      if(scope){
        if(scope.row._data.ugc !== '{REPLACE}' && scope.row._data.username !== '{REPLACE}'){
          this.tableData[scope.$index]._data.inputVal = '';
        }
      }
    },
    
    async loginStatus(){  //批量提交接口

      try{
        if(this.multipleSelection.length === 0){
          return;
        }

        let words = [];

        for(let i = 0,len = this.multipleSelection.length; i < len; i++){
          const _data = this.multipleSelection[i]._data;
          const { ugc, username, find, inputVal} = _data;
          if(inputVal === '' && ugc === '{REPLACE}' && username === '{REPLACE}'){
            continue;
          }
          let item = '';

          if(ugc === '{REPLACE}' && username === '{REPLACE}'){
            item = `${find}=${inputVal}`
          } else if(ugc === '{REPLACE}' && username !== '{REPLACE}'){
            item = `${find}=${username}|${inputVal}`
          } else if(username === '{REPLACE}' && ugc !== '{REPLACE}'){
            item = `${find}=${inputVal}|${ugc}`
          } else if(username !== '{REPLACE}' && ugc !== '{REPLACE}'){
            item = `${find}=${username}|${ugc}`
          }

          words.push(item);
        }

        if(words.length === 0){
          return;
        }

        await this.appFetch({
          url:'batchSubmit',
          method:'post',
          standard: false,
          data:{
            "data": {
              "type": "stop-words",
              "words": words
          }
          }
        })
        this.handleSearchUser(true);
      } catch(err){
        console.error(err,'function loginStatus error')
      }
      
    },
    tableContAdd(){
        this.tableData.push({
          _data:{
            find:"",
            username:"",
            ugc:"",
            addInputFlag:true,
          }
        })
    },
    deleteWords(){
      this.deleteList = []
      for(var i =0;i<this.multipleSelection.length;i++){
        this.deleteList.push(this.multipleSelection[i]._data.id)
      }
      console.log(this.deleteList.join(","))
      this.appFetch({
        url:'deleteWords',
        method:'delete',
        splice:this.deleteList.join(","),
        data:{
          
        }
      }).then(res=>{
        this.handleSearchUser(true);
        console.log(res)
      })
      
    }
  
  },
  components:{
    Card,
    CardRow,
    TableContAdd,
    Page
  }
}
