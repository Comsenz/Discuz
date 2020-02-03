<template>
    <div class="pay-box">
      <PayHeader></PayHeader>
      <p class="pay-box-title">支付订单查询</p>
      <van-loading type="spinner" color="#1989fa" />
    </div>
</template>

<script>
import PayHeader from '../../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'

export default {
  name: "pay-view",
  data:function () {
    return {

    }
  },
  methods:{

  },
  components:{
    PayHeader
  },
}
</script>

<style lang="less">
.pay-box{
  text-align: center;
  .pay-box-title{
    margin-top: 60px;
  }
}
</style>
