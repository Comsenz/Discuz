<template>
  <div>
    <Card :header="query.typeName"></Card>

    <Card header="通知标题：" v-if="query.type==='system'">
      <CardRow description="系统发送的信息标题，不支持HTML，不超过75字节">
        <el-input type="text" maxlength="75" v-model="noticeTitle" ></el-input>
      </CardRow>
    </Card>

    <Card header="通知内容：" v-if="query.type==='system'">
      <CardRow row
        description="系统发送的信息内容，标题内容均支持变量替换，可以使用如下变量:<br>
                    {username}：用户名
                    {groupname} ：所属用户组
                    {time}：发送时间
                    {sitename}：网站名称（显示在页面底部的联系方式名称）
                    {bbname}：站点名称（显示在浏览器窗口标题等位置的名称）
                    {adminemail}：管理员Email
                    {content}：内容
                    {reason}：原因"
      >
        <el-input type="textarea" :autosize="{ minRows: 5, maxRows: 5}" v-model="noticeContent" clearable></el-input>
      </CardRow>
    </Card>

    <Card header="模板ID" v-if="query.type==='wx'">
      <CardRow row :description="wxNoticeDescription[query.id]">
        <el-input type="text" maxlength="75" v-model="wxNoticeCon" ></el-input>
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
