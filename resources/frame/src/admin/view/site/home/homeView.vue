<template>
  <div class="home-box">

    <Card class="home-card-box" id="other_content_header" intercept="true" style="border:0;padding:0;">
    </Card>

    <Card class="home-card-box" header="待处理事项" intercept="true">
      <p class="pending-p">
        等待审核的主题数
        <router-link
          to="/admin/cont-review"
          style="color: #336699;"
        >(&nbsp;{{siteInfo.unapprovedThreads}}&nbsp;)</router-link>
      </p>
      <p class="pending-p">
        等待审核的回复数
        <router-link
          to="/admin/reply-review"
          style="color: #336699;"
        >(&nbsp;{{siteInfo.unapprovedPosts}}&nbsp;)</router-link>
      </p>
      <p class="pending-p">
        等待审核的提现数
        <router-link
          to="/admin/withdrawal-application"
          style="color: #336699;"
        >(&nbsp;{{siteInfo.unapprovedMoneys}}&nbsp;)</router-link>
      </p>
      <p class="pending-p">
        等待审核的用户数
        <router-link
          to="/admin/user-review"
          style="color: #336699;"
        >(&nbsp;{{siteInfo.unapprovedUsers}}&nbsp;)</router-link>
      </p>
    </Card>

    <Card class="home-card-box" header="系统信息" intercept="true">
      <p class="section">
        <span class="section-title">Discuz!程序版本：</span>
        <span>{{siteInfo.version}}</span>
        <span v-if="newVersion" class="section-title section-title-right">
          <span>[</span>
          <a href="https://discuz.com/docs/release_notes.html" target="_blank">新版本： {{versionNumber}}</a>
          <span>]</span>
        </span>
      </p>
      <p class="section">
        <span class="section-title">服务器系统及PHP：</span>
        <span>{{siteInfo.php_version}}</span>
      </p>
      <p class="section">
        <span class="section-title">服务器软件：</span>
        <span>{{siteInfo.server_software}}</span>
      </p>
      <p class="section">
        <span class="section-title">服务器MySQL版本：</span>
        <span>{{siteInfo.db}}</span>
      </p>
      <p class="section">
        <span class="section-title">上传许可：</span>
        <span>{{siteInfo.upload_size}}</span>
      </p>
      <p class="section">
        <span class="section-title">当前数据库尺寸：</span>
        <span>{{siteInfo.db_size}}</span>
      </p>
    </Card>

    <Card class="home-card-box home-card__footer" id="other_content_footer" header="相关链接">
      <a href="https://discuz.chat/manual-admin/" target="_blank">使用手册</a>
      <a href="https://www.discuz.com" target="_blank">Discuz! Q 官方</a>
      <a href="https://www.discuz.chat" target="_blank">支持论坛</a>
      <a href="https://cloud.tencent.com/" target="_blank">腾讯云</a>
      <a href="http://www.dnspod.cn/" target="_blank">DNSPod</a>
      <a href="https://da.do/hmos" target="_blank">免费SSL证书</a>
    </Card>
  </div>
</template>

<script>
import "../../../scss/site/module/globalStyle.scss";
import homeCon from "../../../controllers/site/home/homeCon";
export default {
  name: "home-view",
  ...homeCon,
};
</script>
