<template>
    <div>
      内容过滤设置
    </div>
</template>

<script>
import contentFilteringSetCon from '../../../controllers/site/global/contentFilteringSetCon';
import '../../../scss/site/pageStyle.scss';
export default {
    name: "content-filtering-set-view",
  ...contentFilteringSetCon
}
</script>
