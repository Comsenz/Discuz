/*
*  资金明细
* */

import Card from '../../../view/site/common/card/card';
import moment from 'moment';

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

      userName:'',
      changeTime:['',''],
      changeDescription:'',
    }
  },
  methods:{

    searchClick(){
      if (this.changeTime == null){
        this.changeTime = ['','']
      } else if(this.changeTime[0] !== '' && this.changeTime[1] !== ''){
        this.changeTime[0] = this.changeTime[0] + '-00-00-00';
        this.changeTime[1] = this.changeTime[1] + '-24-00-00';
      }
      this.getFundingDetailsList();
    },

    /*
    * 格式化日期
    * */
    formatDate(data){
      return moment(data).format('YYYY-MM-DD HH:mm')
    },


    /*
    * 接口请求
    * */
    getFundingDetailsList(){
      this.appFetch({
        url:'walletDetails',
        method:'get',
        data:{
          include:['user','userWallet'],
          'filter[username]' : this.userName,
          'filter[change_desc]' : this.changeDescription,
          'filter[start_time]' : this.changeTime[0],
          'filter[end_time]' : this.changeTime[1]
        }
      }).then(res=>{
        console.log(res);
        this.tableData = [];
        this.tableData = res.readdata
      }).catch(err=>{
        console.log(err);
      })
    }

  },
  created(){
    this.getFundingDetailsList();
  },
  components:{
    Card
  }
}
