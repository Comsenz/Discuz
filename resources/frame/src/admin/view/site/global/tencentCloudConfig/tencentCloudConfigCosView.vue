<template>
    <div>
      <Card header="对象存储配置"></Card>
      <Card header="名称：">
        <CardRow description="填写存储桶基本配置中的空间名称">
          <el-input v-model="cosName" clearable></el-input>
          <template #tail>
            <a href="https://cloud.tencent.com/product/cos" target="_blank">未申请？点此申请</a>
          </template>
        </CardRow>
      </Card>
      <Card header="地域：">
        <CardRow description="填写存储桶基本配置中的所属地域，例如：ap-beijing">
          <el-input v-model="cosArea" clearable></el-input>
        </CardRow>
      </Card>
      <Card header="数据万象处理域名：">
        <CardRow description="填写存储桶基本配置中的访问域名">
          <el-input v-model="cosDomainName" clearable></el-input>
        </CardRow>
      </Card>
      <Card class="footer-btn">
        <el-button type="primary" size="medium" @click="submission">提交</el-button>
      </Card>
    </div>
</template>

<script>
  import tencentCloudConfigCosCon from '../../../../controllers/site/global/tencentCloundSet/tencentCloudConfigCosCon'
  export default {
      name: "tencentCloudConfigCosView",
    ...tencentCloudConfigCosCon
  }
</script>

