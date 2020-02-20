/**
 * 我的通知
 */

import MyNoticeHeader from '../../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import browserDb from '../../../../../../helpers/webDbHelper';
export default {
  data:function () {
    return {
      num:{
        replied: {
          title: '回复我的',
          typeId: 1,
          number: 0,
          routerName: 'reply'
        },
        rewarded:{
          title: '打赏我的',
          typeId: 3,
          number: 0,
          routerName: 'reward'
        },
        liked:{
          title: '点赞我的',
          typeId: 2,
          number: 0,
          routerName: 'like'
        },
        system:{
          title: '系统通知',
          typeId: 4,
          number: 0,
          routerName: 'system'
        }
      }
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
          this.$router.push('/system');
      }
    },
    notice(){
      var userId = browserDb.getLItem('tokenId');
      this.appFetch({
        url:'users',
        method:'get',
        splice:'/'+userId,
        standard: false,
        data:{
        }
      }).then(res=>{
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          // throw new Error(res.error)
        } else {
          const data = res.data.attributes.typeUnreadNotifications;

          for(let key in data) {
            if(this.num[key]) {
              this.num[key].number = data[key];
            }
          }
        }
      })
    },
  },
  components:{
    MyNoticeHeader
  },


  }
