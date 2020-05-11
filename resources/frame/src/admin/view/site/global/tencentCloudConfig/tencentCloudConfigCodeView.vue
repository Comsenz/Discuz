<template>
  <div>
    <Card header="验证码配置"></Card>
    <Card header="APPID：">
      <CardRow description="腾讯云帐号 - 验证码 - APPID列表中，详情基础配置中的APPID">
        <el-input v-model="appId" clearable></el-input>
        <template #tail>
          <a href="https://cloud.tencent.com/product/captcha" target="_blank">未申请？点此申请</a>
        </template>
      </CardRow>
    </Card>

    <Card header="App Secret Key：">
      <CardRow description="腾讯云帐号 - 验证码 - APPID列表中，详情基础配置中的App Secret Key">
        <el-input v-model="secretId" clearable></el-input>
      </CardRow>
    </Card>

    <Card class="footer-btn">
      <el-button
        type="primary"
        size="medium"
        @click="Submission"
        id="TencentCaptcha"
        data-appid="appId"
      >提交</el-button>
    </Card>
  </div>
</template>

<script>
import "../../../../scss/site/module/globalStyle.scss";
import tencentCloudConfigCodeCon from "../../../../controllers/site/global/tencentCloundSet/tencentCloudConfigCodeCon";

export default {
  name: "tencent-cloud-config-code-view",
  ...tencentCloudConfigCodeCon
};
</script>