<template>
    <div class="home-box">
      <Card class="home-card-box" header="待处理事项" intercept="true">
        <p>
          等待审核的主题数
          <span style="color: #336699;">(&nbsp;1&nbsp;)</span>
        </p>
      </Card>

      <Card class="home-card-box" header="系统信息" intercept="true">
        <p>
          <span>Discuz!程序版本</span>
          <span>{{this.siteInfo.version}}</span>
        </p>
        <p>
          <span>服务器系统及PHP</span>
          <span>{{this.siteInfo.php_version}}</span>
        </p>
        <p>
          <span>服务器软件</span>
          <span>{{this.siteInfo.server_software}}</span>
        </p>
        <p>
          <span>服务器MySQL版本</span>
          <span></span>
        </p>
        <p>
          <span>上传许可</span>
          <span>{{this.siteInfo.upload_size}}</span>
        </p>
        <p>
          <span>当前数据库尺寸</span>
          <span>{{this.siteInfo.db_size}}</span>
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
import '../../../scss/site/globalStyle.scss';
import homeCon from '../../../controllers/site/home/homeCon';
export default {
    name: "home-view",
  ...homeCon
}
</script>
