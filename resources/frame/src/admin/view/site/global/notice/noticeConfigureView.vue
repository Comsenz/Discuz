<template>
  <div>
    <Card header="配置"></Card>

    <Card header="用户角色通知标题：">
      <CardRow description="系统发送的欢迎信息的标题，不支持HTML，不超过75字节
">
        <el-input type="text" maxlength="75" v-model="noticeTitle" show-word-limit clearable></el-input>
      </CardRow>
    </Card>

    <Card header="用户角色通知内容：">
      <CardRow
        description="系统发送的欢迎信息的内容，标题内容均支持变量替换，可以使用如下变量:
                    {usename}：用户名
                    {time}：发送时间
                    {sitename}：网站名称（显示在页面底部的联系方式名称）
                    {bbname}：站点名称（显示在浏览器窗口标题等位置的名称）
                    {adminemail}：管理员Email
                    双击输入框可扩大/缩小    "
      >
        <el-input type="textarea" :autosize="{ minRows: 5, maxRows: 5}" v-model="noticeContent" clearable></el-input>
      </CardRow>
    </Card>

    <Card class="footer-btn">
      <el-button type="primary" size="medium" @click="Submission">提交</el-button>
    </Card>
  </div>
</template>

<script>
import "../../../../scss/site/module/globalStyle.scss";
import noticeConfigureCon from "../../../../controllers/site/global/notice/noticeConfigureCon";

export default {
  name: "notice-configure-view",
  ...noticeConfigureCon
};
</script>