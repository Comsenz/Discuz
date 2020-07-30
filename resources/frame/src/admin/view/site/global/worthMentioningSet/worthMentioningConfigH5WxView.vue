<template>
  <div class="worth-mentioning-config-h5-box">
    <Card :header="typeCopywriting[type].title"></Card>

    <Card header="APPID：">
      <CardRow :description="typeCopywriting[type].appIdDescription">
        <el-input v-model="appId"></el-input>

        <template #tail>
          <a :href="typeCopywriting[type].url" target="_blank" style="margin-left: 15px;">未申请？点此申请</a>
        </template>
      </CardRow>
    </Card>

    <Card header="App secret：">
      <CardRow :description="typeCopywriting[type].appSecretDescription">
        <el-input v-model="appSecret"></el-input>
      </CardRow>
    </Card>

    <Card header="开启小程序视频功能：" v-if="type === 'wx_miniprogram'">
      <CardRow description="开启后，在小程序前台将展示视频内容，并且可进行视频内容的发布。开启前，请务必确保您的小程序已有相应的视频播放类目的权限。具体类目权限请">
        <el-switch
          v-model="closeVideo"
          active-color="#336699"
          inactive-color="#bbbbbb"
        >
        </el-switch>
        <template #tail>
          <a :href="typeCopywriting[type].url" target="_blank">点此查看</a>
        </template>
      </CardRow>
    </Card>

    <!-- <Card header="服务器地址(URL)：" v-if="type === 'wx_offiaccount'">
      <CardRow :description="typeCopywriting[type].serverUrl">
        <p>{{serverUrl}}</p>
      </CardRow>
    </Card>

    <Card header="令牌(Token)：" v-if="type === 'wx_offiaccount'">
      <CardRow :description="typeCopywriting[type].appToken">
        <el-input v-model="appToken"></el-input>

        <template #tail>
          <span class="random-btn"  @click="randomClick('token')">随机生成</span>
        </template>
      </CardRow>
    </Card>

    <Card header="消息加解密密匙(EncodingAESKey)：" v-if="type === 'wx_offiaccount'">
      <CardRow :description="typeCopywriting[type].encodingAESKey">
        <el-input v-model="encodingAESKey"></el-input>

        <template #tail>
          <span class="random-btn" @click="randomClick('aes')">随机生成</span>
        </template>
      </CardRow>
    </Card> -->

    <Card class="footer-btn">
      <el-button type="primary" size="medium" @click="submitConfiguration" >提交</el-button>
    </Card>
  </div>
</template>

<script>
import '../../../../scss/site/module/globalStyle.scss';
import worthMentioningConfigH5WxCon from '../../../../controllers/site/global/worthMentioningSet/worthMentioningConfigH5WxCon';
export default {
    name: "worth-mentioning-config-view",
  ...worthMentioningConfigH5WxCon
}
</script>
