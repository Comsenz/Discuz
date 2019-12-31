/*
*  提现申请
* */

import Card from '../../../../view/site/common/card/card';
import moment from "moment/moment";
import Page from '../../../../view/site/common/page/page';
import webDb from 'webDbHelper';

export default {
  data:function () {
    return {
      tableData: [],
      cashSn:'',                  //流水号
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
      applicationTime: ['',''],   //申请时间
      operationUser:'',           //操作用户
      statusOptions: [
        {
          value: '1',
          label: '待审核'
        },
        {
          value: '2',
          label: '审核通过'
        },
        {
          value: '3',
          label: '审核不通过'
        },
        {
          value: '4',
          label: '待打款'
        },
        {
          value: '5',
          label: '已打款'
        },
        {
          value: '6',
          label: '打款失败'
        }
      ],
      statusSelect: '1',          //状态选中
      visible:false,

      total:0,                    //总数
      pageCount:0,                //总页数
      currentPaga:1               //第几页
    }
  },
  methods:{

    cashStatus(status,data){
      switch (status){
        case 1:
          if (!data.error_message){
            return '待审核'
          } else {
            return "待审核，原因：" + data.error_message;
          }
          break;
        case 2:
          return "审核通过";
          break;
        case 3:
          return "审核不通过，原因：" + data.remark;
          break;
        case 4:
          return "待打款";
          break;
        case 5:
          return "已打款";
          break;
        case 6:
          return "打款失败，原因：" + data.error_message;
          break;
        default:
          console.log("获取状态失败，请刷新页面！");
          return "未知状态";
      }
    },

    noReviewClick(id){
      let data = {id:[]};
      this.$MessageBox.prompt('', '提示', {
        confirmButtonText: '提交',
        cancelButtonText: '取消',
        inputPlaceholder:'请输入不通过理由'
      }).then((value)=>{
        data.id.push(id);
        data.status = 3;
        data.remark = value.value;
        this.postReview(data);
      }).catch((err) => {
        console.log(err);
      });
    },

    reviewClick(id){
      let data = {id:[]};
      data.id.push(id);
      data.status = 2;
      this.postReview(data);
    },

    searchClick(){
      if (this.applicationTime == null){
        this.applicationTime = ['','']
      } else if(this.applicationTime[0] !== '' && this.applicationTime[1] !== ''){
        this.applicationTime[0] = this.applicationTime[0] + '-00-00-00';
        this.applicationTime[1] = this.applicationTime[1] + '-24-00-00';
      }
      this.currentPaga = 1;
      this.getReflectList();
    },

    handleCurrentChange(val){
      console.log(val);
      this.currentPaga = val;
      this.getReflectList();
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
    getReflectList(){
      this.appFetch({
        url:'reflect',
        method:'get',
        data:{
          include:['user','userWallet'],
          'filter[cash_status]':this.statusSelect,
          'page[number]':this.currentPaga,
          'page[size]':10,
          'filter[cash_sn]':this.cashSn,
          'filter[username]':this.operationUser,
          'filter[start_time]':this.applicationTime[0],
          'filter[end_time]':this.applicationTime[1]
        }
      }).then(res=>{
        console.log(res);
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
        }else {
          this.tableData = [];
          this.tableData = res.readdata;

          this.total = res.meta.total;
          this.pageCount = res.meta.pageCount;
        }
      }).catch(err=>{
        console.log(err);
      })
    },
    postReview(data){
      this.appFetch({
        url:'review',
        method:'post',
        standard: false,
        data:{
          'ids':data.id,
          'cash_status':data.status,
          'remark':data.remark
        }
      }).then(res=>{
        console.log(res);
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
        }else {
          if (res.data.result[data.id] === 'success') {
            this.getReflectList();
            this.$message({
              message: '提交成功！',
              type: 'success'
            });
          } else if (res.data.result[data.id] === 'failure') {
            this.$message.error('提交错误！请重新提交');
          } else {
            this.$message.error('未知错误，请刷新页面后重新提交');
          }
        }
      }).catch(err=>{
        console.log(err);
      })
    },

    getCreated(state){
      if(state){
        console.log(state);
        this.currentPaga = 1;
      } else {
        console.log(state);
        this.currentPaga = Number(webDb.getLItem('currentPag'))||1;
      }
      this.getReflectList();
    }
  },
  created(){
    // this.getReflectList(Number(webDb.getLItem('currentPag'))||1);
  },
  beforeRouteEnter (to,from,next){
    next(vm => {
      if (to.name !== from.name && from.name !== null){
        console.log('执行');
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
