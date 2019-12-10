
import Card from '../../../view/site/common/card/card';
import CardRow from '../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {

    }
  },
  methods:{
    submi(){ //提交附件信息
      this.appFetch({
        url:'settings',
        method:'post',
        data:{
          
        }
      }).then(data=>{

      })
    }
  },
  components:{
    Card,
    CardRow
  }
}
