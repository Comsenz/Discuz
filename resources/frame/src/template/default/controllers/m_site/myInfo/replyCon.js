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
      replyList:{
        // imgUrl:'',
        // stateTitle:"回复我的",
        // time:"十分钟前",
        // userName:"Elizabeth",
        // type:1,
      }
     
    }
  },
  components:{
    ReplyHeader,
    ContHeader,
    ContMain,
    ContFooter
  },
  mounted(){
    this.myReply()
  },
  methods:{
    myReply(){
      this.appFetch({
        url:'notification',
        method:'get',
        data:{
          type:'1'
        }
      }).then((res)=>{
        this.replyList = res.data
      })
    }
  },
  created(){
    this.imgUrl = "../../../../../../../static/images/mytx.png"
  }
}
