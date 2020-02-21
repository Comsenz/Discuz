<template>
    <div class="card-row-box">
      <div class="card-row-lf">
        <slot></slot>
      </div>
      <div class="card-row-rf">
        <span v-if="!row">{{$attrs.description}}</span>
        <span v-else v-html="fixText($attrs.description)"></span>
        <slot name="tail"></slot>
      </div>

    </div>
</template>

<script>
import '../../../../scss/site/module/common/card/cardRow.scss';
export default {
  name: "form-row",
  data:function () {
    return {

    }
  },
  props:{
    row:{
      type:Boolean,  //传入row开启换行，需要在换行的地方添加<br/>
      default:false
    }
  },
  methods:{
    fixText (text) {
      let replaceRegex = /(\n\r|\r\n|\r|\n)/g;
      text = text || '';
      return text.replace(replaceRegex, "<br/>");
    }
  }
}
</script>

