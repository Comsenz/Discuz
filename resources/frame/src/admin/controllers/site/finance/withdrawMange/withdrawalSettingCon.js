/*
*  提现设置
* */

import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      withdrawalInterval:'',   //提现间隔时间
      withdrawalFee:'',        //提现手续费率
      minAmount:'',            //最小金额
      maxAmount:'',            //最大金额
      amountCap:''             //金额上限
    }
  },
  methods:{
    submitClick(){
      this.postWithdrawalSettings();
    },

    /*
    * 请求接口
    * */
    postWithdrawalSettings(){
      this.appFetch({
        url:'settings',
        method:'post',
        data:{
          data:[
            {
              "attributes":{
                "key":"cash_interval_time",
                "value":this.withdrawalInterval,
                "tag": "cash"
              }
            },
            {
              "attributes":{
                "key":"cash_rate",
                "value":this.withdrawalFee,
                "tag": "cash"
              }
            },
            {
              "cash_min_sum":{
                "key":"cash_interval_time",
                "value":this.minAmount,
                "tag": "cash"
              }
            },
            {
              "attributes":{
                "key":"cash_max_sum",
                "value":this.maxAmount,
                "tag": "cash"
              }
            },
            {
              "attributes":{
                "key":"cash_sum_limit",
                "value":this.amountCap,
                "tag": "cash"
              }
            }
          ]
        }
      }).then(res=>{
        this.$message({
          message: '提交成功',
          type: 'success'
        });
        this.getForum();

      }).catch(err=>{
        console.log(err);
        this.$message.error('操作失败！');
      })
    },
    getForum(){
      this.appFetch({
        url:'forum',
        method:'get',
        data:{}
      }).then(res=>{
        let formData = res.data.attributes.setcash;
        this.withdrawalInterval = formData.cash_interval_time;
        this.withdrawalFee = formData.cash_rate;
        this.minAmount = formData.cash_min_sum;
        this.maxAmount = formData.cash_max_sum;
        this.amountCap = formData.cash_sum_limit;
      }).catch(err=>{
        console.log(err);
        this.$message.error('初始化失败！请重新刷新页面（F5）');
      })
    }

  },
  created(){
    this.getForum();
  },
  components:{
    Card,
    CardRow
  }
}
