<template>
    <div>
      <Card header="视频配置"></Card>
      <Card header="转码模板：">
       <CardRow description="腾讯云账户 - 云点播 - 视频转码模板中的模板ID">
          <el-input v-model="vodTranscode" clearable></el-input>
        </CardRow>
      </Card>
      <Card header="支持的视频扩展名:">
        <CardRow description="多个请用,隔开，例如 WMV、RM、MOV、MPEG、MP4、3GP、FLV、AVI、RMVB">
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
import '../../../../scss/site/module/globalStyle.scss';
import tencentCloudConfigVodCon from '../../../../controllers/site/global/tencentCloundSet/tencentCloudConfigVodCon';
export default {
    name: "tencent-cloud-config-sms-view",
  ...tencentCloudConfigVodCon
}
</script>
