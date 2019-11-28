/**
 * 打赏我的
 */

//问题：打赏我的字体，应该跟发布的主题内容一致吧。直接打赏的是主题，并不是引用回复这种。

import RewardHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import ContHeader from '../../../view/m_site/common/cont/contHeaderView'
import ContMain from '../../../view/m_site/common/cont/contMainView'
import ContFooter from '../../../view/m_site/common/cont/contFooterView'


export default {
  data:function () {
    return {
      rewardList:[],
      imgUrl:'',
      // stateTitle:'打赏了我1000元',
      // time:"10分钟前",
      // userName:'发送到发斯蒂芬',
      // contText:"摩西摩西，土豆那里去挖？"
    }
  },
  components:{
    RewardHeader,
    ContHeader,
    ContMain,
    ContFooter
  },
  mounted(){
    this.myReward()
  },
  methods:{
    myReward(){
      console.log('2222222222')
      this.apiStore.find('notice', {type:3}).then(res=>{
        this.rewardList = res
      })
    }
  },
  created(){
    this.imgUrl = '../../../../../../../static/images/mytx.png';
  },
}
