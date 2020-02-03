<template>
    <div class="home-box">
      <Card class="home-card-box" header="待处理事项" intercept="true">
        <p class="pending-p">
          等待审核的主题数
          <router-link to="/admin/cont-review" style="color: #336699;">(&nbsp;{{siteInfo.unapprovedThreads}}&nbsp;)</router-link>
        </p>
        <p class="pending-p">
          等待审核的回复数
          <router-link to="/admin/reply-review" style="color: #336699;">(&nbsp;{{siteInfo.unapprovedPosts}}&nbsp;)</router-link>
        </p>
        <p class="pending-p">
          等待审核的提现数
          <router-link to="/admin/withdrawal-application" style="color: #336699;">(&nbsp;{{siteInfo.unapprovedMoneys}}&nbsp;)</router-link>
        </p>
      </Card>

      <Card class="home-card-box" header="系统信息" intercept="true">
        <p class="section">
          <span>Discuz!程序版本</span>
          <span>{{siteInfo.version}}</span>
        </p>
        <p class="section">
          <span>服务器系统及PHP</span>
          <span>{{siteInfo.php_version}}</span>
        </p>
        <p class="section">
          <span>服务器软件</span>
          <span>{{siteInfo.server_software}}</span>
        </p>
        <p class="section">
          <span>服务器MySQL版本</span>
          <span>{{siteInfo.db}}</span>
        </p>
        <p class="section">
          <span>上传许可</span>
          <span>{{siteInfo.upload_size}}</span>
        </p>
        <p class="section">
          <span>当前数据库尺寸</span>
          <span>{{siteInfo.db_size}}</span>
        </p>
      </Card>

      <Card class="home-card-box home-card__footer" header="相关链接">
        <a href="https://www.discuz.net/" target="_blank">Discuz!&nbsp;支持论坛</a>
        <a href="https://cloud.tencent.com/" target="_blank">腾讯云</a>
        <a href="http://www.dnspod.cn/" target="_blank">DNSPod</a>
      </Card>

    </div>
</template>

<script>
import '../../../scss/site/module/globalStyle.scss';
import homeCon from '../../../controllers/site/home/homeCon';
export default {
    name: "home-view",
  ...homeCon
}
</script>
