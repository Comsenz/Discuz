
import WithdrawHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import Panenl from '../../../view/m_site/common/panel';

export default {
  data:function () {
    return {
      withdrawalsList:[
        // {
        //   cash_status:"审核通过，打款中",
        //   cash_apply_amount:"-1809",
        //   cash_sn:'',
        //   created_at:'',
        // },
       
      ],
      cashStatusObj:{
        1:'待审核',
        2:'审核通过',
        3:'审核不通过',
        4:'待打款',
        5:'已打款',
        6:'打款失败'
      }
    }
  },

  components:{
    WithdrawHeader,
    Panenl
  },
  mounted(){
    this.reflect()
  },
  methods:{
    reflect(){
      this.appFetch({
        url:'reflect',
        method:'get',
        data:{
          include:''
        }
      },(res)=>{

      }).then((res)=>{
        this.withdrawalsList = res.data;
        console.log(this.withdrawalsList)
      })
    }
  }
  }
