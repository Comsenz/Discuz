/**
 * 回复我的
 */

import ReplyHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import ContHeader from '../../../view/m_site/common/cont/contHeaderView'
import ContMain from '../../../view/m_site/common/cont/contMainView'
import ContFooter from '../../../view/m_site/common/cont/contFooterView'


export default {
  data:function () {
    return {
      imgUrl:'',
      stateTitle:"回复我的",
      time:"十分钟前",
      userName:"Elizabeth"
    }
  },
  components:{
    ReplyHeader,
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
