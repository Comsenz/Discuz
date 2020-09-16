<template>
  <div>
    <Card header="UCenter配置"></Card>
    
    <Card header="APPID：">
      <CardRow description="填写UCenter中该应用的APPID">
        <el-input v-model="valueAppid"></el-input>
      </CardRow>
    </Card>

    <Card header="UCenter地址：">
      <CardRow description="填写 UCenter 的 URL，结尾请不要加“/”">
        <el-input v-model="valueUrl"></el-input>
      </CardRow>
    </Card>
    
    <Card header="通信秘钥：">
      <CardRow class="span-right" description="只允许使用英文字母及数字，限 64 字节。应用端的通信密钥必须与此设置保持一致，否则该应用将无法与 UCenter 正常通信">
        <el-input v-model="valueKey"></el-input>
      </CardRow>
    </Card>

    <Card class="footer-btn" >
      <el-button type="primary" size="medium" @click="submitConfiguration">提交</el-button>
    </Card>
  </div>
</template>

<script>
import '../../../../scss/site/module/globalStyle.scss';
import worthMentioningConfigUcenter from '../../../../controllers/site/global/worthMentioningSet/worthMentioningConfigUcenter'
export default {
    name: "worth-mentioning-config-ucenter",
  ...worthMentioningConfigUcenter
}
</script>