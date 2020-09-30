<template>
  <div>
    <Card header="微信支付配置"></Card>

    <Card header="APPID：">
      <CardRow description="appid是微信公众帐号或小程序的唯一标识">
        <el-input v-model="appId"></el-input>
      </CardRow>
    </Card>

    <Card header="微信支付的商户号（mch_id）：">
      <CardRow description="商户申请微信支付后，由微信支付分配的商户收款帐号">
        <el-input v-model="mchId"></el-input>
      </CardRow>
    </Card>

    <Card header="API密匙（key）：">
      <CardRow description="交易过程生成签名的密钥">
        <el-input v-model="apiKey"></el-input>
      </CardRow>
    </Card>
    <Card header="已开通企业付款到零钱产品">
      <CardRow class="card-pay" description="开通后才可使用企业向个人付款，付款资金将直接进入用户零钱的功能。否则将采用提交手机号，人工打款到微信手机账号的方式。">
        <el-switch
          v-model="value"
          active-color="#336699"
          inactive-color="#bbbbbb"
        >
        </el-switch>
      </CardRow>
    </Card>
    <!-- <Card header="iOS下开启支付功能">
      <CardRow description="开启后，iOS设备中付费模式才可正常使用，且免费模式下才可设置内容付费以及使用打赏功能">
        <el-switch
          v-model="iOSPay"
          active-color="#336699"
          inactive-color="#bbbbbb"
        >
        </el-switch>
      </CardRow>
    </Card>  -->

    <!-- <Card header="App Secret：">
      <CardRow description="App Secret是APPID对应的接口密码，用于获取接口调用凭证access_token时使用">
        <el-input v-model="appSecret"></el-input>
      </CardRow>
    </Card> -->

    <Card class="footer-btn" >
      <el-button type="primary" size="medium" @click="submitConfiguration">提交</el-button>
    </Card>
  </div>
</template>

<script>
import '../../../../scss/site/module/globalStyle.scss';
import pyaConfigWxCon from '../../../../controllers/site/global/paySet/payConfigWxCon';
export default {
    name: "pay-config-wx-view",
  ...pyaConfigWxCon
}
</script>
