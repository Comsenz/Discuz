<template>
    <div class="cont-arrange-box">
      <div class="cont-arrange-main">
        <div class="cont-arrange__lf-side">
          <slot name="side"></slot>
        </div>
        <main class="cont-arrange__rt-main">
          <div class="cont-arrange__rt-main-header">
            <slot name="header"></slot>
          </div>
          <div class="cont-arrange__rt-main-box">
            <slot name="main"></slot>
          </div>
          <div class="cont-arrange__rt-main-footer" v-if="$slots.footer">
            <slot name="footer"></slot>
          </div>
        </main>
      </div>
      <!--多列，获取到父组件传过来数组的长度，生成多个模板-->
      <div class="cont-arrange-main">
        <div class="cont-arrange__lf-side">
          <slot name="side"></slot>
        </div>
        <main class="cont-arrange__rt-main">
          <div class="cont-arrange__rt-main-header">
            <slot name="header"></slot>
          </div>
          <div class="cont-arrange__rt-main-box">
            <slot name="main"></slot>
          </div>
          <div class="cont-arrange__rt-main-footer" v-if="$slots.footer">
            <slot name="footer"></slot>
          </div>
        </main>
      </div>
    </div>
</template>

<script>
import  "../../../../scss/site/common/cont/contArrage.scss"
export default {
    name: "cont-arrange-view"
}
</script>
