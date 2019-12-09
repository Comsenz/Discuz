
import orderDetailsHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import Panenl from '../../../view/m_site/common/panel';

export default {
  data:function () {
    return {
      orderList:{

      },
      type:{
        1:'注册',
        2:'打赏'
      },
      status:{
        0:'待付款',
        1:'已付款'
      }
    }
  },

  components:{
    orderDetailsHeader,
    Panenl
  },
  mounted(){
    this.order()
  },
  methods:{
    order(){
      this.appFetch({
        url:'orderList',
        method:'get',
        data:{
          include:''
        }
      }).then((res)=>{
        this.orderList = res.data
      })
    }
  }
}
