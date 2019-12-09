/**
 * 我的通知
 */

import MyNoticeHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'

export default {
  data:function () {
    return {
      num:[

      ],
      
    }
  },
  mounted(){
    this.notice()//我的通知里点赞我的
  },
  methods:{
    myJump(str){
      switch (str) {
        case 'reply':
          this.$router.push('/reply');
          break;
        case 'reward':
          this.$router.push('/reward');
          break;
        case 'like':
          this.$router.push('/like');
          break;
        default:
          this.$router.push('/');
      }
    },
    notice(){
      console.log("1111111111")
      this.appFetch({
        url:'noticeList',
        method:'get',
        data:{
          describe:'', //我的通知页面里点赞我的
          include:''
        }
      }).then(res=>{
        this.num = [];
        res.data.forEach((item)=>{
          this.num.push(item.index+1)
          console.log(this.num)
        })
      })
    },
  
  },
  components:{
    MyNoticeHeader
  },


  }

