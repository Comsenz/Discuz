<template>

</template>

<script>
import webDb from '../../../../../helpers/webDbHelper';
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

    if (webDb.getSItem('num') || webDb.getSItem('num') > 1){
      if (this.$router.history.current.query.url === '/'){
        this.$router.go(0);
      }
      webDb.setSItem('num',2);
    } else {
      webDb.setSItem('num',1);
    }


    // if (!this.$router.history.current.query.state){
    //   this.$router.replace({path:this.$router.history.current.query.url});
    // }
  }
}
</script>

<style scoped>

</style>
