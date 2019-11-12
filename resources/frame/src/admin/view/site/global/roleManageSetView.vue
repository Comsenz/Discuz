<template>
    <div>
      角色管理
    </div>
</template>

<script>
import roleManageSetCon from '../../../controllers/site/global/roleManageSetCon';
import '../../../scss/modules/element-variables.scss';
export default {
    name: "role-manage-set-view",
  ...roleManageSetCon
}
</script>
