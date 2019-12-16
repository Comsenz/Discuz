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
      replyList:[
 
      ]
     
    }
  },
  components:{
    ReplyHeader,
    ContHeader,
    ContMain,
    ContFooter
  },
  created(){
    this.imgUrl = "../../../../../../../static/images/mytx.png",
    this.myReplyList()
  },
  methods:{
    myReplyList(){
      this.appFetch({
        url:'notice',
        method:'get',
        data:{
          type:'1'
        }
      }).then(res=>{
        console.log(res)
        this.replyList = res.readdata;
      })
    }
  },

}
