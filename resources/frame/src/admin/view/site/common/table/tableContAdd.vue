<template>
    <div class="table-cont-add-box">
      <p @click="tableContAddClick">
        <span class="iconfont iconicon_add icon-add"></span>
        <span>{{$attrs.cont}}</span>
      </p>
    </div>
</template>

<script>
import "../../../../scss/module/site/common/table/tableContAdd.scss";
import tableContAddCon from '../../../../controllers/site/common/table/tableContAddCon';
export default {
    name: "table-cont-add-view",
  ...tableContAddCon
}
</script>
