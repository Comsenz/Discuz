<template>
    <div class="panel-box">

      <div class="panel-header">
        <div class="panel-header-lf">
          <span>{{titles}}</span>
        </div>

        <div class="panel-header-rh">
            <span :class="this.nums >= 0?'add-orange':''">{{nums}}</span>
          <!-- <span :class="parseInt(this.nums) >= 0?'add-orange':''">{{parseInt(this.nums) >= 0?'+'+nums:nums}}</span> -->
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
