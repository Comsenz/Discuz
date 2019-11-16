
import Card from '../../../view/site/common/card/card';
import CardRow from '../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
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
      value: '',    //主题分类选择值

      pageOptions: [{
        value: '选项1',
        label: '每页显示10条'
      }, {
        value: '选项2',
        label: '每页显示20条'
      }, {
        value: '选项3',
        label: '每页显示30条'
      }],
      pageSelect:'',    //每页显示数选择值

      checkedStatus:false,  //更多选项

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
      },    //快捷选择时间

      dataValue:'',     //发表时间范围

      replyType:3,   //被回复数介于类型
      topType:6    //置顶主题类型

    }
  },
  methods:{
    checkboxChange(str){
      //优化：
      //展开需要时间，没完全展开时，高度不同时，就移动了，就需要展开完整后再移动滚动条。
      //不能随着滚动条变化而变化

      setTimeout(()=>{
        if (str){
          let gd =  document.getElementsByClassName('index-main-con__main')[0];
          gd.scrollTo(0,gd.scrollHeight);
        }
      },300);

    }
  },
  components:{
    Card,
    CardRow
  }
}
