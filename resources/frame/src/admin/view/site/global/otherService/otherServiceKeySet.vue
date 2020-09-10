<template>
  <div>
    <Card header="腾讯位置服务帐号"></Card>

    <Card header="key：">
      <CardRow description="腾讯位置服务帐号 - 控制台 - key与配额 - key管理的key">
        <el-input v-model="key"></el-input>
      </CardRow>
    </Card>

    <Card class="footer-btn" >
      <el-button type="primary" size="medium" @click="submitConfiguration">提交</el-button>
    </Card>
  </div>
</template>

<script>
import '../../../../scss/site/module/globalStyle.scss';
import pyaConfigWxCon from '../../../../controllers/site/global/otherService/otherServiceKeySetCon';
export default {
    name: "other-service-key-set-view",
  ...pyaConfigWxCon
}
</script>
