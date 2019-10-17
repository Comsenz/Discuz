<template>
    <div>
      <p>用户管理</p>
    </div>
</template>

<script>
import userCon from 'userCon';
import '../../scss/userView.scss'
export default {
    name: "user-view",
  ...userCon
}
</script>
