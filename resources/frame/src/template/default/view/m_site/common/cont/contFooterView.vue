<template>
    <footer class="cont-footer-box">
      <div class="cont-footer-main">
        <div class="cont-footer-top">

          <div class="cont-footer-like">
            <span class="icon iconfont icon-praise-after"></span>
            <a href="javascript:;">Elizabetch</a>，<a href="javascript:;">sdfdsfsd</a>，<a href="javascript:;">第三方第三方</a>，<a href="javascript:;">电风扇</a>，<a href="javascript:;">dfffss</a>&nbsp;
            <span>等21个人觉得很赞</span>
          </div>

          <div class="cont-footer-praise">
            <span class="icon iconfont icon-money"></span>
            <a href="javascript:;">Thomas</a>，<a href="javascript:;">Lily</a>,<a href="javascript:;">Johnny</a>,<a href="javascript:;">Henry</a>
          </div>

        </div>
        <div class="cont-footer-btm">
          <p>
            <a href="javascript:;">Jason</a>
            <span>:</span>
            <span>手动给赞，每天被概念搞的晕头转向</span>
          </p>

          <p>
            <a href="javascript:;">Elizabeth</a>
            <span>
              回复
              <a href="/n/Jason">jason</a>：im286了解一下
            </span>
          </p>
          <p>全部27条回复</p>
        </div>
      </div>
    </footer>
</template>

<script>
import '../../../../less/m_site/common/cont/contFooter.less';
import contFooterCon from '../../../../controllers/m_site/common/contCon/contFooterCon';
export default {
    name: "cont-footer-view",
  ...contFooterCon
}
</script>
