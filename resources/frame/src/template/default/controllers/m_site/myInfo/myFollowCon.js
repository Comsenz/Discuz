/**
 * 我的通知
 */

import MyNoticeHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import browserDb from '../../../../../helpers/webDbHelper';
export default {
  data:function () {
    return {
      num:{
        myCare: {
          title: '我关注的人',
          typeId: 1,
          number: 0,
          routerName: 'myCare'
        },
        careMe:{
          title: '关注我的人',
          typeId: 2,
          number: 0,
          routerName: 'careMe'
        }
      }
    }
  },
  mounted(){
  },
  methods:{
    myJump(str){
      console.log(str,'路由');
      switch (str) {
        case 'myCare':
          this.$router.push('/my-care');
          break;
        default:
          this.$router.push('/care-me');
      }
    },
  },
  components:{
    MyNoticeHeader
  },


  }
