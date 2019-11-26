<template>
    <div>
      <Card header="短信配置"></Card>

      <Card header="短信验证码使用模板ID：">
        <CardRow description="填写在腾讯云已配置并审核通过的短信验证码的模板的ID">
          <el-input></el-input>
          <template #tail>
            <span  style="color: #336699;margin-left: 15PX" >未申请？点此申请</span>
          </template>
        </CardRow>
      </Card>

      <Card header="短信签名：">
        <CardRow description="腾讯云账户 - 访问管理 - 访问密钥 - API密钥的SecretId">
          <el-input></el-input>
        </CardRow>
      </Card>

      <Card >
        <el-button type="primary" size="medium" @click="$router.push({path:'/admin/tencent-cloud-set'})" >提交</el-button>
      </Card>
    </div>
</template>

<script>
import '../../../../scss/site/globalStyle.scss';
import tencentCloudConfigSmsCon from '../../../../controllers/site/global/tencentCloundSet/tencentCloudConfigSmsCon';
export default {
    name: "tencent-cloud-config-sms-view",
  ...tencentCloudConfigSmsCon
}
</script>
