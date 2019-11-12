<template>
    <div>
      腾讯云设置
    </div>
</template>

<script>
import tencentCloudSetCon from '../../../controllers/site/global/tencentCloudSetCon';
import '../../../scss/site/pageStyle.scss';
export default {
   name: "tencent-cloud-set-view",
  ...tencentCloudSetCon
}
</script>
