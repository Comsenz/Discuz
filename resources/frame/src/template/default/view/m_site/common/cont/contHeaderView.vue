<template>
  <header class="cont-header-box">
    <div class="avatar-box">
      <a href="/home-page" class="img-box">
        <img :src="imgUrl" alt="用户头像">
      </a>
    </div>

    <div class="text-box">
      <h3 class="user-name">
        <span class="user-name-text">Elizabeth</span>
        <span class="text-status">{{this.$attrs.stateTitle}}</span>
      </h3>
      <h4 class="time">{{this.$attrs.time}}</h4>
    </div>

    <div class="operating-box">
      <slot name="operating"></slot>
    </div>


  </header>
</template>

<script>
import '../../../../less/m_site/common/cont/contHeader.less';
import contHeaderCon from '../../../../controllers/m_site/common/contCon/contHeaderCon'
export default {
    name: "cont-header-view",
  ...contHeaderCon
}
</script>

