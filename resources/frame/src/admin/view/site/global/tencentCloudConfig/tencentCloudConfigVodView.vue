<template>
  <div>
    <Card header="视频配置"></Card>
    <Card header="子应用：">
      <CardRow description="请填写子应用 ID，如果不填写、填写0或填写开发者的腾讯云 AppId，则操作的子应用为“主应用”。">
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
    <Card header="支持的视频扩展名:">
      <CardRow description="多个请用,隔开，例如 WMV,RM,MOV,MPEG,MP4,3GP,FLV,AVI,RMVB">
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
