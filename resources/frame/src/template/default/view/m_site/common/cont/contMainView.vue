<template>
    <article class="cont-main-box">
      <div class="cont-main-text">
        <!--我们来看一下程序员经常去的 14 个顶级开发者社区，如果你还不知道它们，那么赶紧去看看，也许会有意想不到的收获。-->
        <!--{{this.contText}}-->
        {{this.$attrs.contText}}
      </div>
      <div class="cont-media-wraps">
        <img src="../../../../../../../static/images/mytx.png" alt="上传图片">
      </div>
    </article>
</template>

<script>
import '../../../../less/m_site/common/cont/contMain.less';
import contHeaderCon from '../../../../controllers/m_site/common/contCon/contMainCon'
export default {
    name: "cont-main-view",
  ...contHeaderCon
}
</script>
