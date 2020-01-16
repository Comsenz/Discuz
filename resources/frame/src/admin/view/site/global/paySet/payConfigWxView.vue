<template>
  <div>
    <Card header="微信支付配置"></Card>

    <Card header="APPID：">
      <CardRow description="appid是微信公众账号或开放平台APP的唯一标识">
        <el-input v-model="appId"></el-input>
      </CardRow>
    </Card>

    <Card header="微信支付的商户号（mch_id）：">
      <CardRow description="商户申请微信支付后，由微信支付分配的商户收款账号">
        <el-input v-model="mchId"></el-input>
      </CardRow>
    </Card>

    <Card header="API密匙（key）：">
      <CardRow description="交易过程生成签名的密钥">
        <el-input v-model="apiKey"></el-input>
      </CardRow>
    </Card>

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
