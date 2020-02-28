<template>
    <div class="panel-box" @click="boxClick">

      <div class="panel-header">
        <div class="panel-header-lf">
          <span>{{titles}}</span>
        </div>

        <div class="panel-header-rh">
          <span :class="status?'add-orange':''">
            {{nums}}
          </span>
        </div>
      </div>

      <div class="panel-bottom" @click="labelClick">
        <slot name="label"></slot>
      </div>

    </div>
</template>

<script>
import panelCon from '../../../controllers/m_site/common/panelCon'
import '../../../defaultLess/m_site/common/common.less';

export default {
    name: "panel",
  ...panelCon
}

</script>
