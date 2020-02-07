/**
 * 我的通知
 */

import MyNoticeHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import browserDb from '../../../../../helpers/webDbHelper';
export default {
  data:function () {
    return {
      num:{
        replied: {
          title: '我关注的人',
          typeId: 1,
          number: 0,
          routerName: 'myCare'
        },
        rewarded:{
          title: '关注我的人',
          typeId: 3,
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
      switch (str) {
        case 'reply':
          this.$router.push('/myCare');
          break;
        default:
          this.$router.push('/careMe');
      }
    },
  },
  components:{
    MyNoticeHeader
  },


  }
