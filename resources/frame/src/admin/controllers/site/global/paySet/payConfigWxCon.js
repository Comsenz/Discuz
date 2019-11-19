
import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {

  }
  },
  methods:{
    configClick(){
      this.loginStatus = 'wx'
    }
  },
  components:{
    Card,
    CardRow
  }
}
