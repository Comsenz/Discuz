<template>
  <header class="cont-header-box">
    <div class="avatar-box">
      <a :href="'/home-page/'+$attrs.userId" class="img-box">
        <img :src="imgUrl" alt="用户头像" />
<!--        <img v-if="$attrs.isReal" class="icon-yirenzheng" src="/static/images/authIcon.svg" alt="实名认证">-->
      </a>
    </div>

    <div class="text-box">
      <h3 class="user-name">
        <a class="user-name-text" :href="'/home-page/'+$attrs.userId">{{$attrs.userName}}</a>
        <span class="text-status">{{$attrs.stateTitle}}{{$attrs.amount}}</span>
      </h3>
      <h4 class="time">{{$attrs.time}}</h4>
    </div>

    <div class="operating-box">
      <slot name="operating"></slot>
    </div>


  </header>
</template>

<script>
import '../../../../defaultLess/m_site/common/common.less'
import contHeaderCon from '../../../../controllers/m_site/common/contCon/contHeaderCon'
export default {
    name: "cont-header-view",
  ...contHeaderCon
}
</script>

