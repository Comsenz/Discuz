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
      amountCap:'',            //金额上限
      subLoading:false,        //提现按钮状态
    }
  },
  methods:{
    submitClick(){
      this.subLoading = true;
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
              "attributes":{
                "key":"cash_min_sum",
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
        this.subLoading = false;
        if (res.errors){
          res.errors.forEach((item,index)=>{
            setTimeout(()=>{
              this.$message.error(item.detail[0])
            },(index+1) * 500);
          });
        }else {
          this.$message({
            message: '提交成功',
            type: 'success'
          });
          this.getForum();
        }

      }).catch(err=>{
        this.$message.error('操作失败！');
      })
    },
    getForum(){
      this.appFetch({
        url:'forum',
        method:'get',
        data:{}
      }).then(res=>{
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          let formData = res.data.attributes.set_cash;
          this.withdrawalInterval = formData.cash_interval_time;
          this.withdrawalFee = formData.cash_rate;
          this.minAmount = formData.cash_min_sum;
          this.maxAmount = formData.cash_max_sum;
          this.amountCap = formData.cash_sum_limit;
        }
      }).catch(err=>{
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
