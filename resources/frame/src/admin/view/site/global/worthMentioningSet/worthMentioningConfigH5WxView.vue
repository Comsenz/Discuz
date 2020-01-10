<template>
  <div class="worth-mentioning-config-h5-box">
    <Card :header="typeCopywriting[type].title"></Card>

    <Card header="APPID：">
      <CardRow :description="typeCopywriting[type].appIdDescription">
        <el-input v-model="appId"></el-input>

        <template #tail>
          <a :href="typeCopywriting[type].url" target="_blank" style="margin-left:15px;">未申请？点此申请</a>
        </template>
      </CardRow>
    </Card>


    <Card header="App secret：">
      <CardRow :description="typeCopywriting[type].appSecretDescription">
        <el-input v-model="appSecret"></el-input>
      </CardRow>
    </Card>

    <Card class="footer-btn">
      <!-- <el-button type="primary" size="medium" @click="$router.push({path:'/admin/worth-mentioning-set'})" >提交</el-button> -->
      <el-button type="primary" size="medium" @click="submitConfiguration" >提交</el-button>
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
