
import Card from '../../../view/site/common/card/card';
import CardRow from '../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      checked:true,
      pwdLength:'',   //密码长度
      checkList:[],   //密码规则
    }
  },
  methods:{

  },
  components:{
    Card,
    CardRow
  }
}
