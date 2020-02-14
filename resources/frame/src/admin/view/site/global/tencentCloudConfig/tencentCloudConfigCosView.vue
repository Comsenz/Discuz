<template>
    <div>
      <Card header="对象存储配置"></Card>
      <Card header="名称：">
        <CardRow description="">
          <el-input v-model="cosName" clearable></el-input>
        </CardRow>
      </Card>
      <Card header="地域：">
        <CardRow description="">
          <el-input v-model="cosArea" clearable></el-input>
        </CardRow>
      </Card>
      <Card header="数据万象处理域名：">
        <CardRow description="">
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

