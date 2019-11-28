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
      replyList:[]
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
      this.apiStore.find('notice', {type:1}).then(res => {
        console.log(res[0].user_id(), res[0].detail().post_content);
        this.replyList = res;
      });
      // this.appFetch({
      //   url:'notice',
      //   method:'get',
      //   data:{
      //     type:'1'
      //   }
      // }).then((res)=>{
      //   console.log(res);
      //   // this.replyList = res.data
      //   // console.log(res.data[0].attributes.data.user_name)
      // })
    },
    deleteReply(index){
      this.replyList.splice(index,1)
    }
  },
  created(){
    this.imgUrl = "../../../../../../../static/images/mytx.png"
  }
}
