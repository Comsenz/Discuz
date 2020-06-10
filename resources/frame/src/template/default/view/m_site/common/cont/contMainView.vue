<template>
    <article class="cont-main-box">
      <div class="cont-main-text">
        {{$attrs.contText}}
      </div>
      <div class="cont-media-wraps">
        <img src="/static/images/noavatar.gif" alt="上传图片">
      </div>
    </article>
</template>

<script>
import contHeaderCon from '../../../../controllers/m_site/common/contCon/contMainCon'
export default {
    name: "cont-main-view",
  ...contHeaderCon
}
</script>
