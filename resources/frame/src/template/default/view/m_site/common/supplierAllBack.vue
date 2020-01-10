<template>

</template>

<script>
export default {
  name: "supplier-all-back",
  data:function () {
    return {

    }
  },
  methods:{

  },
  created(){
    this.$router.replace({path:this.$router.history.current.query.url});
    // if (!this.$router.history.current.query.state){
    //   this.$router.replace({path:this.$router.history.current.query.url});
    // }
  }
}
</script>

<style scoped>

</style>
