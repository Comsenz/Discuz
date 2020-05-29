<template>
  <div>
    <Card header="视频配置"></Card>
    <Card header="子应用：">
      <CardRow description="请填写子应用 ID，如果没有开启子应用，此处留空即可，留空将自动采用主应用。">
        <el-input v-model="subApplication" clearable></el-input>
        <template #tail>
          <a href="https://cloud.tencent.com/product/vod" target="_blank">未申请？点此申请</a>
        </template>
      </CardRow>
    </Card>

    <Card header="转码模板：">
      <CardRow
        description="腾讯云账户 - 云点播 - 视频转码模板中的模板ID。还需在回调设置中配置下回调URL，回调URL为：http(s)://当前域名/api/threads/notify/video，回调事件请选择”视频上传完成回调“和”视频转码完成回调“。"
      >
        <el-input v-model="vodTranscode" clearable></el-input>
      </CardRow>
    </Card>

    <Card header="水印模板：">
      <CardRow
        description="腾讯云账户 - 云点播 - 视频处理设置 - 模板设置 - 水印模板中的模板ID，如果不填写则不启用视频水印。"
      >
        <el-input v-model="vodTranscode" clearable></el-input>
      </CardRow>
    </Card>

    <Card header="截图模板：">
      <CardRow description="请填写腾讯云账户 - 云点播 - 视频处理设置 - 模板设置 - 截图模板中的模板ID，如果不填写则采用默认截图模板。">
        <el-input v-model="screenshot" clearable></el-input>
      </CardRow>
    </Card>

    <Card header="动图封面任务流名称：">
      <CardRow
        description="请填写腾讯云账户 - 云点播 - 视频处理设置 - 任务流设置中的任务流名称。如果创建新任务流，必须勾选“转动图”，选择合适的转动图gif模板并设置合适的时间段。如果填写了动图模板名称，则“截图模板”设置失效。"
      >
        <el-input v-model="vodTaskflowGif" clearable></el-input>
      </CardRow>
    </Card>

    <Card header="云点播防盗链 Key：">
      <CardRow description="请填写腾讯云账户 - 云点播 - 分发播放设置 - 域名管理 - 设置中的Key 防盗链。">
        <el-input v-model="vodUrlKey" clearable></el-input>
      </CardRow>
    </Card>

    <Card header="云点播防盗链签名有效期：">
      <CardRow
        description="单位秒。过期后该 URL 将不再有效，返回 403 响应码。考虑到机器之间可能存在时间差，防盗链 URL 的实际过期时间一般比指定的过期时间长 5 分钟，即额外给出 300 秒的容差时间。建议过期时间戳不要过短，确保视频有足够时间完整播放。"
      >
        <el-input v-model="vodUrlExpire" clearable></el-input>
      </CardRow>
    </Card>
    <Card header="支持的视频扩展名:">
      <CardRow description="多个请用,隔开，例如 wmv,rm,mov,mpeg,mp4,3gp,flv,avi,rmvb">
        <el-input v-model="vodExt" clearable></el-input>
      </CardRow>
    </Card>

    <Card header="支持的最大尺寸:">
      <CardRow description="单位：MB">
        <el-input v-model="vodSize" clearable></el-input>
      </CardRow>
    </Card>

    <Card class="footer-btn">
      <el-button type="primary" size="medium" @click="Submission">提交</el-button>
    </Card>
  </div>
</template>

<script>
import "../../../../scss/site/module/globalStyle.scss";
import tencentCloudConfigVodCon from "../../../../controllers/site/global/tencentCloundSet/tencentCloudConfigVodCon";
export default {
  name: "tencent-cloud-config-sms-view",
  ...tencentCloudConfigVodCon
};
</script>
