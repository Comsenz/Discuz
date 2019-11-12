<template>
    <div>
      后台用户管理
    </div>
</template>

<script>
import userManageSetCon from '../../../controllers/site/global/userManageSetCon';
import '../../../scss/site/pageStyle.scss';
export default {
    name: "user-manage-set-view",
  ...userManageSetCon
}
</script>
