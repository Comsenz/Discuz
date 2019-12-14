/*
*  订单记录
* */

import Card from '../../../view/site/common/card/card';
import webDb from 'webDbHelper';
import moment from "moment/moment";


export default {
  data:function () {
    return {
      tableData: [],
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

      orderNumber:'',
      operationUser:'',
      commodity:'',
      orderTime:['','']
    }
  },
  methods:{

    searchClick(){
      if (this.orderTime == null){
        this.orderTime = ['','']
      } else if(this.orderTime[0] !== '' && this.orderTime[1] !== ''){
        this.orderTime[0] = this.orderTime[0] + '-00-00-00';
        this.orderTime[1] = this.orderTime[1] + '-24-00-00';
      }
      this.getOrderList();
    },

    /*
    * 请求接口
    * */
    getOrderList(){
      this.appFetch({
        url:'orderList',
        method:'get',
        data:{
          include:['user','thread',''],
          'filter[order_sn]':this.orderNumber,
          'filter[product]':this.commodity,
          'filter[username]':this.operationUser,
          'filter[start_time]':this.orderTime[0],
          'filter[end_time]':this.orderTime[1]
        }
      }).then(res=>{
        console.log(res);
        this.tableData = [];
        this.tableData = res.readdata;
      }).catch(err=>{
        console.log(err);
      })
    }
  },
  created(){
    this.getOrderList();
  },
  components:{
    Card
  }
}
