<template>
    <div class="home-box">
      首页
    </div>
</template>

<script>
import '../../../scss/pageStyle.scss';
import homeCon from '../../../controllers/home/homeCon';
export default {
    name: "home-view",
  ...homeCon
}
</script>
