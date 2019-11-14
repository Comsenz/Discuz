<template>
  <div class="card-box">
    <div class="card-box__header" :class="$slots.default?'':'not-main'" >
      <header class="card-title">{{$attrs.header}}</header>
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

