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
      <Card header="访问域名：">
        <CardRow description="填写存储桶基本配置中的访问域名">
          <el-input v-model="cosDomainName" clearable></el-input>
        </CardRow>
      </Card>
      <Card header="启用签名：">
        <CardRow description="开启后，请将存储桶访问权限改为私有读写，此时附件将使用签名加密方式，每次生成的链接仅一定周期内有效">
          <el-switch
            v-model="cosSignUrl"
            active-color="#336699"
            inactive-color="#bbbbbb"
          ></el-switch>
        </CardRow>
      </Card>
      <Card header="启用文档预览：">
        <CardRow description="开启服务前，请先在对象存储中开启文档能力。启用后对于当前对象存储中的文档资源，可实现文档资源预览。">
          <el-switch
            v-model="cosDocPreview"
            active-color="#336699"
            inactive-color="#bbbbbb"
          ></el-switch>
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

