<template>
  <div class="face-container">
    <div class="scroll-wrapper">
      <div class="face-content"
        @touchmove.prevent
        ref="faceContent"
        :style="{ width: scrollWidth + 'px', marginLeft: -scrollPosition + 'px' }"
      >
        <div class="face-page"
          v-for="(page, index) in faces"
          :key="index">
          <a v-for="(item, j) in page"
            :key=j>
            <img @click="onFaceClick(`[${item.title}]`)" :src='realmName+item.attributes.url' class="emoji">
          </a>
        </div>
      </div>
      <div class="page-dot">
        <div v-for="n in faces.length"
          @click="active = n - 1"
          :key=n class="dot-item"
          :class="n === (active + 1) ? 'active' : ''">
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import mSiteExpression from '../../../controllers/m_site/common/expressionCon';
  import '../../../scss/m_site/mobileIndex.scss';
  export default {
    name: "expressionView",
    ...mSiteExpression
  }
</script>
