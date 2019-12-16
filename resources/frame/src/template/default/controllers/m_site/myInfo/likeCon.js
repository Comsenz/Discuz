/**
 * 点赞我的
 */

import LikeHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import ContHeader from '../../../view/m_site/common/cont/contHeaderView'
import ContMain from '../../../view/m_site/common/cont/contMainView'
import ContFooter from '../../../view/m_site/common/cont/contFooterView'


export default {
  data:function () {
    return {
      likeList:[
        
      ]
    }
  },
  components:{
    LikeHeader,
    ContHeader,
    ContMain,
    ContFooter
  },
  methods:{
    myLikeList(){
      this.appFetch({
        url:'notice',
        method:'get',
        data:{
          type:'2'
        }
      }).then(res=>{
        console.log(res)
        this.likeList = res.readdata;
      })
    }
  },
  created(){
    this.imgUrl = "../../../../../../../static/images/mytx.png"
    this.myLikeList()
  }
}
