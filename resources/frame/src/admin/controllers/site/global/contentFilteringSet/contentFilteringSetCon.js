/*
* 内容过滤设置
* */

import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';
import TableContAdd from '../../../../view/site/common/table/tableContAdd';
import Page from '../../../../view/site/common/page/page';
import webDb from '../../../../../helpers/webDbHelper'
import appConfig from "../../../../../../config/appConfig";

export default {
  data:function () {
    let token = webDb.getLItem('Authorization');

    return {
      tableData: [],
      multipleSelection: [],
      tableDataLength:'',
      // disabled:true,
      createCategoriesStatus:false,   //添加分类状态
      exportUrl: appConfig.devApiUrl+'/api/stop-words/export?token=Berer ' + token,
      options: [{
          value: '{IGNORE}',
          label: '不处理'
        }, {
          value: '{MOD}',
          label: '审核'
        },{
          value: '{BANNED}',
          label: '禁用'
        },
        {
          value: '{REPLACE}',
          label: '替换'
        }
      ],

      optionsUser: [{
        value: '{IGNORE}',
        label: '不处理'
      },{
        value: '{BANNED}',
        label: '禁用'
      },
      // {
      //   value: '{MOD}',
      //   label: '审核'
      // }
    ],
      serachVal:'',
      checked:false,
      searchData :[],//搜索后的数据
      replace:true,
      inputFind:false,
      radio2:"1",
      total:0, //总条数
      pageLimit:20, //每页多少条
      pageNum:0, //当前页
      userLoadMoreStatus: true,
      userLoadMorePageChange: false,
      // loginStatus:'',  //default  batchSet
      deleteStatus:true,
      // contentParams: {
      //   'filter[p]': '',
      //   'page[number]': 1,
      // }

      deleteList:[],
      tableAdd:false,

    }
  },
  created(){
    this.handleSearchUser(true);  //初始化页面数据
    // this.pageNum  = Number(webDb.getLItem('currentPag'))||1;
    // this.handleSearchUser(Number(webDb.getLItem('currentPag'))||1);
  },
  beforeRouteEnter(to,from,next){
    next(vm => {
      if (to.name !== from.name && from.name !== null){
        console.log('执行');
        vm.getCreated(true)
      }else {
        console.log('不执行');
        vm.getCreated(false)
      }
    })
  },
  methods:{
    getCreated(state){
      if(state){
        console.log(state);
        this.pageNum  = 1
      } else {
        console.log(state);
        this.pageNum  = Number(webDb.getLItem('currentPag'))||1;
      };
      this.handleSearchUser(true)

    },
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
      this.pageNum = 1
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
        if (response.errors){
          this.$message.error(response.errors[0].code);
        }else {
          if (initStatus) {
            this.tableData = [];
          }

          this.tableData = this.tableData.concat(response.readdata).map((v) => {
            if (v._data.inputVal === undefined) {
              v._data.inputVal = '';
            }
            console.log(response)
            this.total = response.meta.total;
            // this.pageNum = response.meta.pageCount;
            // this.total = response.meta ? response.meta.total : 0;
            return v;
          });
          console.log(this.tableData)
        }
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

      let result = this.tableData.filter((v)=>{
        return v._data.addInputFlag;
      })

      result = result.concat(this.multipleSelection);

      try{
        if(this.tableData.length === 0){
          return;
        }

        let words = [];

        for(let i = 0,len = this.tableData.length; i < len; i++){
          const _data = this.tableData[i]._data;
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
            item = `${find}=${ugc}|${username}`
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
              "words": words,
              "overwrite":true
          }
          }
        })
        // if (res.errors){
        //   this.$message.error(res.errors[0].code);
        // }else{
          // this.pageNum  = 1
          this.handleSearchUser(true);
          this.$message({message: '提交成功', type: 'success'});
        // }

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
            inputVal: "",
            addInputFlag:true,
          }
        })
        this.tableAdd = true
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
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else{
          this.handleSearchUser(true);
        }
      })

    },
    handleCurrentChange(val){
      this.pageNum = val
      this.handleSearchUser(true)
    }

  },
  components:{
    Card,
    CardRow,
    TableContAdd,
    Page
  }
}
