<template>
  <div class="annex-set-box">
    <Card header="支持的图片扩展名：">
      <CardRow description="多个请用,隔开，例如 png,gif,jpg">
        <el-input v-model="picture"></el-input>
      </CardRow>
    </Card>

    <Card header="支持的文件扩展名：">
      <CardRow description="多个请用,隔开，例如 doc,docx,pdf,zip">
        <el-input v-model="fileExtension"></el-input>
      </CardRow>
    </Card>

    <Card header="支持的最大尺寸：">
      <CardRow description="单位：MB">
        <el-input v-model="maximumSize" type="number"></el-input>
      </CardRow>
    </Card>

    <Card class="footer-btn">
      <el-button type="primary" size="medium" @click="submi">提交</el-button>
    </Card>

  </div>
</template>

<script>
import annexSetCon from '../../../controllers/site/global/annexSetCon';
import '../../../scss/module/site/globalStyle.scss';
export default {
    name: "annex-set-view",
  ...annexSetCon
}
</script>
