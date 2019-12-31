/*
* 钱包
* */

import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      options: [
        {
          value: 1,
          label: '增加余额'
        }, 
        {
          value: 2,
          label: '减少余额'
        }
      ],
      walletInfo: {
        user:{
          _data:{}
        },
        _data: {}
      },
      operateType: '',
      operateAmount: '',
      value: '',
      textarea:'',
      query: {}
    }
  },

  created(){
    this.query = this.$route.query;
    this.getWalletDet();
  },

  methods:{
    async getWalletDet(){
      try{
        if(this.query.id === undefined){
          throw new Error('not found user id');
        }
        const response = await this.appFetch({
          method: 'get',
          url: 'wallet',
          splice: `${this.query.id ? this.query.id : ''}`
        })
        console.log(response,'wallet response')
        this.walletInfo = response.readdata;
      }catch(err){
        console.error(err, 'getWalletDet');
      }
    },

    operaAmountInput(val){
      this.operateAmount = val.replace(/[^0-9^\.]/g,'');
    },

    async handleSubmit(){
      try{
        if(this.query.id === undefined){
          return;
        }
        if(this.operateType){
          var datas = {
            user_id: Number(this.query.id),
            operate_type: this.operateType,
            operate_amount: parseFloat(this.operateAmount),
            operate_reason: this.textarea,
            wallet_status: this.walletInfo._data.wallet_status
          }
        }else{
          var datas ={
            user_id: Number(this.query.id),
            wallet_status: this.walletInfo._data.wallet_status
          }
         
        }
        await this.appFetch({
          method: 'patch',
          url: 'wallet',
          splice: this.query.id,
          data: datas
        }).then(data=>{
          if (data.errors){
            this.$message.error(data.errors[0].code);
          }else{
          this.$message({ message: '提交成功', type: 'success' });
          this.getWalletDet();
          }
        })

      } catch(err){
        console.error(err,'handleSubmit ')
      }
    },
    
  },

  components:{
    Card,
    CardRow
  }
}
