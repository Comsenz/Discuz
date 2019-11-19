<template>
    <div>
      <Card header="云api配置"></Card>

      <Card header="APPID：">
        <CardRow description="腾讯云账户 - 访问管理 - 访问密钥 - API密钥的appid。若使用子账号，权限需覆盖所使用
的服务">
          <el-input></el-input>
        </CardRow>
      </Card>

      <Card header="Secretid：">
        <CardRow description="腾讯云账户 - 访问管理 - 访问密钥 - API密钥的SecretId">
          <el-input></el-input>
        </CardRow>
      </Card>

      <Card header="SecretKey：">
        <CardRow description="腾讯云账户 - 访问管理 - 访问密钥 - API密钥的SecretKey">
          <el-input></el-input>
        </CardRow>
      </Card>

      <Card >
        <el-button type="primary" size="medium"@click="$router.push({path:'/admin/tencent-cloud-set'})" >提交</el-button>
      </Card>

    </div>
</template>

<script>
import '../../../../scss/site/global.scss';
import tencentCloudConfigCloudCon from '../../../../controllers/site/global/tencentCloundSet/tencentCloudConfigCloudCon';

export default {
    name: "tencent-cloud-config-cloud-view",
  ...tencentCloudConfigCloudCon
}
</script>
