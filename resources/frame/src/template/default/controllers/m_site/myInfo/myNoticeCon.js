/**
 * 我的通知
 */

import MyNoticeHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'

export default {
  data:function () {
    return {
      num:[
        {
          title: '回复我的',
          typeId: 1,
          number: 0,
          routerName: 'reply'
        },
        {
          title: '打赏我的',
          typeId: 3,
          number: 0,
          routerName: 'reward'
        },
        {
          title: '点赞我的',
          typeId: 2,
          number: 0,
          routerName: 'like'
        },
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
      this.appFetch({
        url:'noticeList',
        method:'get',
        standard: false,
        data:{
          include:[]
        }
      }).then(res=>{
        const DATA = res.data;
        this.num = this.num.map((val)=>{
          val.number = DATA[val.typeId];
          console.log(val.number)
          return val;
        })
      })
    },
  
  },
  components:{
    MyNoticeHeader
  },


  }

