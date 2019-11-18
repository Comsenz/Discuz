<template>
    <div class="recycle-bin-box">
      <Card header="搜索"></Card>



    </div>
</template>

<script>
import '../../../scss/site/contStyle.scss';
import recycleBinCon from '../../../controllers/site/cont/recycleBinCon'
export default {
    name: "recycle-bin-view",
  ...recycleBinCon
}
</script>
