<template>
    <div>
      <Card header="短信配置"></Card>
      <Card header="SDK AppID:">
        <CardRow description="SDK AppID是短信应用的唯一标识，调用短信API接口时，需要提供该参数">
          <el-input v-model="sdkAppId" clearable></el-input>
        </CardRow>
      </Card>
      <Card header="App Key:">
       <CardRow description="App Key：App Key是用来校验短信发送合法性的密码，与SDK AppID对应">
          <el-input v-model="appKey" clearable></el-input>
        </CardRow>
      </Card>
      <Card header="短信验证码使用模板ID：">
        <CardRow description="填写在腾讯云已配置并审核通过的短信验证码的模板的ID">
          <el-input v-model="smsId" clearable></el-input>
          <template #tail>
            <a href="https://cloud.tencent.com/product/sms" target="_blank">未申请？点此申请</a>
          </template>
        </CardRow>
      </Card>

      <Card header="短信签名：">
        <CardRow description="填写在腾讯云已配置并审核通过的短信签名内容">
          <el-input v-model="smsSignature" clearable></el-input>
        </CardRow>
      </Card>

      <Card class="footer-btn">
        <el-button type="primary" size="medium" @click="Submission">提交</el-button>
      </Card>
    </div>
</template>

<script>
import '../../../../scss/site/module/globalStyle.scss';
import tencentCloudConfigSmsCon from '../../../../controllers/site/global/tencentCloundSet/tencentCloudConfigSmsCon';
export default {
    name: "tencent-cloud-config-sms-view",
  ...tencentCloudConfigSmsCon
}
</script>
