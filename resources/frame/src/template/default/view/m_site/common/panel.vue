<template>
    <div class="panel-box">

      <div class="panel-header">
        <div class="panel-header-lf">
          <span>{{titles}}</span>
        </div>

        <div class="panel-header-rh">
          <span>{{nums}}</span>
        </div>
      </div>

      <div class="panel-bottom">
        <slot name="label"></slot>
      </div>

    </div>
</template>

<script>
import panelCon from '../../../controllers/m_site/common/panelCon'
import '../../../less/m_site/common/panel.less'
export default {
    name: "panel",
  ...panelCon
}
</script>
