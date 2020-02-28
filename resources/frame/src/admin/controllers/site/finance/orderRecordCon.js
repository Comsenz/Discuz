/*
*  订单记录
* */

import Card from '../../../view/site/common/card/card';
import Page from '../../../view/site/common/page/page';
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
      orderTime:['',''],

      pageCount:0,
      currentPaga:1,
      total:0
    }
  },
  methods:{

    cashStatus(status){
      switch (status){
        case 0:
          return "待付款";
          break;
        case 1:
          return "已付款";
          break;
        default:
          //获取状态失败，请刷新页面
          return "未知状态";
      }
    },

    searchClick(){
      if (this.orderTime == null){
        this.orderTime = ['','']
      } else if(this.orderTime[0] !== '' && this.orderTime[1] !== ''){
        this.orderTime[0] = this.orderTime[0] + '-00-00-00';
        this.orderTime[1] = this.orderTime[1] + '-24-00-00';
      };
      this.currentPaga = 1;
      this.getOrderList();
    },

    handleCurrentChange(val){
      this.currentPaga = val;
      this.getOrderList();
    },

    /*
    * 格式化日期
    * */
    formatDate(data){
      return moment(data).format('YYYY-MM-DD HH:mm')
    },

    /*
    * 请求接口
    * */
    getOrderList(){
      this.appFetch({
        url:'orderList',
        method:'get',
        data:{
          include:['user','thread','thread.firstPost'],
          'page[number]':this.currentPaga,
          'page[size]':10,
          'filter[order_sn]':this.orderNumber,
          'filter[product]':this.commodity,
          'filter[username]':this.operationUser,
          'filter[start_time]':this.orderTime[0],
          'filter[end_time]':this.orderTime[1]
        }
      }).then(res=>{
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          this.tableData = [];
          this.tableData = res.readdata;

          this.pageCount = res.meta.pageCount;
          this.total = res.meta.total;
        }
      }).catch(err=>{
      })
    },

    getCreated(state){
      if(state){
        this.currentPaga = 1;
      } else {
        this.currentPaga = Number(webDb.getLItem('currentPag'))||1;
      };
      this.getOrderList();
    }
  },
  created(){
    // this.currentPaga = Number(webDb.getLItem('currentPag'))||1;
    // this.getOrderList(Number(webDb.getLItem('currentPag'))||1);
  },
  beforeRouteEnter (to,from,next){
    next(vm => {
      if (to.name !== from.name && from.name !== null){
        vm.getCreated(true)
      }else {
        vm.getCreated(false)
      }
    })
  },
  components:{
    Card,
    Page
  }
}
