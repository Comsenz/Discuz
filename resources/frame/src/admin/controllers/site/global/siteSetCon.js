
import Card from '../../../view/site/common/card/card';
import CardRow from '../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      radio: '1', //站点模式选择
      radio2:'2'    //关闭站点选择
    }
  },
  methods:{
    radioChange(){
      console.log(this.radio);
    }
  },
  components:{
    Card,
    CardRow
  }
}
