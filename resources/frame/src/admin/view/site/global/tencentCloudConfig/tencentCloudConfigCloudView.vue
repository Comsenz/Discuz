<template>
    <div>
      <Card header="云API配置"></Card>

      <!-- <Card header="APPID：">
        <CardRow description="腾讯云账户 - 访问管理 - 访问密钥 - API密钥的appid。若使用子帐号，权限需覆盖所使用的服务">
          <el-input v-model="appId" clearable></el-input>
          <template #tail>
            <a href="https://console.cloud.tencent.com/cam/capi" target="_blank">点此快速查看</a>
          </template>
        </CardRow>
      </Card> -->

      <Card header="Secretid：">
        <CardRow description="腾讯云账户 - 访问管理 - 访问密钥 - API密钥的SecretId">
          <el-input v-model="secretId" clearable></el-input>
        </CardRow>
      </Card>

      <Card header="SecretKey：">
        <CardRow description="腾讯云账户 - 访问管理 - 访问密钥 - API密钥的SecretKey">
          <el-input v-model="secretKey" clearable></el-input>
        </CardRow>
      </Card>

      <Card class="footer-btn">
        <el-button type="primary" size="medium" @click='Submission'>提交</el-button>
      </Card>

    </div>
</template>

<script>
import '../../../../scss/site/module/globalStyle.scss';
import tencentCloudConfigCloudCon from '../../../../controllers/site/global/tencentCloundSet/tencentCloudConfigCloudCon';

export default {
    name: "tencent-cloud-config-cloud-view",
  ...tencentCloudConfigCloudCon
}
</script>
