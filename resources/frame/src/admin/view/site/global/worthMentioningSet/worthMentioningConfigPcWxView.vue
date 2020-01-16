<template>
  <div>
    <Card header="PC端微信授权登录"></Card>

    <Card header="APPID：">
      <CardRow description="填写申请PC端微信授权登陆后，你获得的APPID">
        <el-input></el-input>
        <template #tail>
          <span style="color: #336699">未申请？点此申请</span>
        </template>
      </CardRow>
    </Card>

    <Card header="App secret：">
      <CardRow description="填写申请PC端微信授权登陆后，你获得的App secret">
        <el-input></el-input>
      </CardRow>
    </Card>

    <Card class="footer-btn">
      <el-button type="primary" size="medium" @click="$router.push({path:'/admin/worth-mentioning-set'})">提交</el-button>
    </Card>
  </div>
</template>

<script>
import '../../../../scss/site/module/globalStyle.scss';
import worthMentioningConfigAppletsCon from '../../../../controllers/site/global/worthMentioningSet/worthMentioningConfigAppletsCon'
export default {
    name: "worth-mentioning-config-pc-wx-view",
  ...worthMentioningConfigAppletsCon
}
</script>
