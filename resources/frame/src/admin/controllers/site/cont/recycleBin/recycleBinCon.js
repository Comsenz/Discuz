/*
* 回收站控制器
* */

import Card from '../../../../view/site/common/card/card';
import ContArrange from '../../../../view/site/common/cont/contArrange';


export default {
  data:function () {
    return {

      tableData: [{
        checkList:['还原'],
        theme:'1主题主题主题主题主题',
        classification:"起舞弄清影",
        author:"小虫",
        replyView:"2/5",
        lastReply:"admin",
        operator:"admin",
        theReason:"文不对题"
      }, {
        checkList:['删除'],
        theme:'2主题主题主题主题主题',
        classification:"起舞弄清影",
        author:"小虫",
        replyView:"3/5",
        lastReply:"admin",
        operator:"admin",
        theReason:"文不对题"
      }, {
        checkList:['还原'],
        theme:'3主题主题主题主题主题',
        classification:"起舞弄清影",
        author:"小虫",
        replyView:"1/5",
        lastReply:"admin",
        operator:"admin",
        theReason:"文不对题"
      }],


      deleteStatus: true,
      multipleSelection: [],


      options: [{
        value: '选项1',
        label: '黄金糕'
      }, {
        value: '选项2',
        label: '双皮奶'
      }, {
        value: '选项3',
        label: '蚵仔煎'
      }, {
        value: '选项4',
        label: '龙须面'
      }, {
        value: '选项5',
        label: '北京烤鸭'
      }],
      value: '',

      pickerOptions: {
        shortcuts: [{
          text: '最近一周',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
            picker.$emit('pick', [start, end]);
          }
        }, {
          text: '最近一个月',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
            picker.$emit('pick', [start, end]);
          }
        }, {
          text: '最近三个月',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
            picker.$emit('pick', [start, end]);
          }
        }]
      },
      value1: '',
      value2: '',

      checkList:[],

      checked:false
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

    handleEdit(index, row) {
      console.log(index, row);
    },
    handleDelete(index, row) {
      console.log(index, row);
    }

  },
  components:{
    Card,
    ContArrange
  }

}
