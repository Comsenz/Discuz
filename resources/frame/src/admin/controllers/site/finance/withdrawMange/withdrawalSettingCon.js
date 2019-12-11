/*
*  提现设置
* */

import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      tableData: [{
        user: '奶罩',
        changeTime: '2016-05-02',
        amountAvailable:"+200.00",
        frozenAmount:'0',
        changeDescription:'管理小虫加入站点“天涯杂谈”，站长奶罩收益100元'
      }, {
        user: '辣椒',
        changeTime: '2016-05-02',
        amountAvailable:"+100.00",
        frozenAmount:'0',
        changeDescription:'小虫加入站点“天涯杂谈”，站长奶罩收益100元'
      }, {
        user: '铁军',
        changeTime: '2016-05-02',
        amountAvailable:"-100.00",
        frozenAmount:'0',
        changeDescription:'铁军提现失败，退回可用余额100元'
      }, {
        user: '王小虎',
        changeTime: '2016-05-02',
        amountAvailable:"+200.00",
        frozenAmount:'0',
        changeDescription:'管理小虫加入站点“天涯杂谈”，站长奶罩收益100元'
      }],
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
    }
  },
  methods:{

  },
  components:{
    Card,
    CardRow
  }
}
