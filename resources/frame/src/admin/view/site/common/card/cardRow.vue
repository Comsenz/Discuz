<template>
    <div class="card-row-box">
      <div class="card-row-lf">
        <slot></slot>
      </div>
      <div class="card-row-rf">
        <span>{{$attrs.description}}</span>
      </div>

    </div>
</template>

<script>
import '../../../../scss/site/common/cardRow.scss';
export default {
    name: "form-row"
}
</script>

