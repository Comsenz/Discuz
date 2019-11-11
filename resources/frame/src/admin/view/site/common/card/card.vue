<template>
  <div class="card-box">
    <div class="card-box__header">
      <header class="card-title">{{this.$attrs.header}}</header>
      <slot name="header"></slot>
    </div>
    <main class="card-box__main">
      <slot></slot>
    </main>
  </div>
</template>

<script>
import '../../../../scss/site/common/card.scss';
// import cardCon from '../'
export default {
    name: "card"
}
</script>

