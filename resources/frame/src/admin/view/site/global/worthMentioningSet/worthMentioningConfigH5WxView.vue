<template>
  <div class="worth-mentioning-config-h5-box">
    <Card header="H5微信授权登录"></Card>

    <Card header="APPID：">
      <CardRow description="填写申请H5微信授权登录后，你获得的APPID">
        <el-input></el-input>

        <template #tail>
          <span  style="color: #336699;margin-left: 15PX;cursor: pointer;" >未申请？点此申请</span>
        </template>
      </CardRow>
    </Card>


    <Card header="App secret：">
      <CardRow description="填写申请H5微信授权登陆后，你获得的App secret">
        <el-input></el-input>
      </CardRow>
    </Card>

    <Card >
      <el-button type="primary" size="medium" @click="$router.push({path:'/admin/worth-mentioning-set'})" >提交</el-button>
    </Card>
  </div>
</template>

<script>
import '../../../../scss/site/globalStyle.scss';
import worthMentioningConfigH5WxCon from '../../../../controllers/site/global/worthMentioningSet/worthMentioningConfigH5WxCon';
export default {
    name: "worth-mentioning-config-view",
  ...worthMentioningConfigH5WxCon
}
</script>
