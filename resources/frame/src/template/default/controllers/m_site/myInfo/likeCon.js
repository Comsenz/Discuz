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
      likeList:[],
      // imgUrl:'',
      // stateTitle:'点赞了我',
      // time:"5分钟前",
      // userName:'Elizabeth'
    }
  },
  components:{
    LikeHeader,
    ContHeader,
    ContMain,
    ContFooter
  },
  mounted(){
    this.myLike()
  },
  methods:{
    myLike(){
      this.apiStore.find('notice',{type:2}).then(res=>{
        console.log(res[0].user_id(), res[0].detail().post_content);
        this.likeList = res
      })
    }
  },
  created(){
    this.imgUrl = "../../../../../../../static/images/mytx.png"
  }
}
