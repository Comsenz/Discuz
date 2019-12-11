/*
*  订单记录
* */

import Card from '../../../view/site/common/card/card';

// import webDb from 'webDbHelper';


export default {
  data:function () {
    return {
      tableData: [{
        orderNumber: '201911251639398878',
        operatingUser: '辣椒',
        productName:"打赏帖子“国庆快乐”",
        amount:'+100.00',
        orderTime:'2018-11-1 13:11',
        status:'支付成功'
      }, {
        orderNumber: '201911251639398878',
        operatingUser: '超超',
        productName:"加入站点“天涯杂谈”",
        amount:'+100.00',
        orderTime:'2018-11-1 13:11',
        status:'支付成功'
      }, {
        orderNumber: '201911251639398878',
        operatingUser: '铁军',
        productName:"付费查看帖子“我的私照”",
        amount:'+100.00',
        orderTime:'2018-11-1 13:11',
        status:'支付成功'
      }, {
        orderNumber: '201911251639398878',
        operatingUser: '小D',
        productName:"打赏帖子“DNSPOD改版了”",
        amount:'+100.00',
        orderTime:'2018-11-1 13:11',
        status:'支付成功'
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
  created(){
    console.log(123);
    // webDb.setLItem('222','111')
  },
  components:{
    Card
  }
}
