<template>
  <div class="card-box">
    <div class="card-box__header"  v-if="$attrs.header" :class="$slots.default?'':'not-main'" >
      <header class="card-title" :class="$attrs.intercept?'card-intercept-title':''" >{{$attrs.header}}</header>
      <slot name="header"></slot>
    </div>
    <main class="card-box__main">

      <slot></slot>

    </main>
  </div>
</template>

<script>
import '../../../../scss/site/common/card/card.scss';
// import cardCon from '../'
export default {
    name: "card"
}
</script>

