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
          <span>Discuz! Q Bete 1.0.12.31</span>
        </p>
        <p>
          <span>服务器系统及PHP</span>
          <span>WINNT/PHP v5.3.29</span>
        </p>
        <p>
          <span>服务器软件</span>
          <span>Microsoft-IIS/7.5</span>
        </p>
        <p>
          <span>服务器MySQL版本</span>
          <span>5.5.53</span>
        </p>
        <p>
          <span>上传许可</span>
          <span>120M</span>
        </p>
        <p>
          <span>当前数据库尺寸</span>
          <span>3.36M</span>
        </p>
        <p>
          <span>当前附件尺寸</span>
          <span><a href="#">[详情]</a></span>
        </p>
      </Card>

      <Card class="home-card-box home-card__footer" header="相关链接">
        <a href="/">Discuz!&nbsp;支持论坛</a>
        <a href="/">腾讯云</a>
        <a href="/">DNSPod</a>
      </Card>

    </div>
</template>

<script>
import '../../../scss/site/pageStyle.scss';
import homeCon from '../../../controllers/site/home/homeCon';
export default {
    name: "home-view",
  ...homeCon
}
</script>
