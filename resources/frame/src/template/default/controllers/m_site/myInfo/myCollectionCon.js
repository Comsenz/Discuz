/**
 * 我的收藏
 */

import CollectionHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import ContHeader from '../../../view/m_site/common/cont/contHeaderView'
import ContMain from '../../../view/m_site/common/cont/contMainView'
import ContFooter from '../../../view/m_site/common/cont/contFooterView'


export default {
  data:function () {
    return {
      imgUrl:'',
      stateTitle:'点赞了我',
      time:"5分钟前",
      userName:'Elizabeth',
      contText:"土豆哪里去挖？土豆一挖一麻袋。"
    }
  },
  components:{
    CollectionHeader,
    ContHeader,
    ContMain,
    ContFooter
  },
  methods:{

  },
  created(){
    this.imgUrl = "../../../../../../../static/images/mytx.png"
  }
}
