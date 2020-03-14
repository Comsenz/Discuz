<template>

</template>

<script>
import webDb from '../../../../../helpers/webDbHelper';
export default {
  name: "supplier-all-back",
  data:function () {
    return {
      status:0
    }
  },
  methods:{

  },

  created(){

    // if (webDb.getSItem('num')) {
    //   webDb.setSItem('num',1);
    //   console.log('第一次进');
    // } else {
    //   console.log('多次进');
    // }

    /*if (webDb.getSItem('num') || webDb.getSItem('num') > 1){

      webDb.setSItem('num',2);
    } else {
      webDb.setSItem('num',1);
    }*/

    /*if (webDb.getLItem('num')){
      // webDb.setSItem('num',2);
      console.log('第二次');
    } else {
      webDb.setLItem('num',1);
      console.log('第一次');
      // webDb.setSItem('num',2);
    }*/

    setTimeout(()=>{
      this.$router.replace({path:this.$router.history.current.query.url});
    },700);


    console.log('空白页' + this.$router.history.current.query.url);

    // this.$router.go(0);

    // if (this.status != 1){
    //   this.$router.go(0);
    // }


    // if (!this.$router.history.current.query.state){
    //   this.$router.replace({path:this.$router.history.current.query.url});
    // }
  }
}
</script>

