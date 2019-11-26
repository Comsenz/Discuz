/**
 * 修改手机号
 */


import ModifyHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'


export default {
  data:function () {
    return {
      phone:'187****1235',
      password:'',
      sms:'',
      newphone:'',
      modifyState:true
    }
  },

  components:{
    ModifyHeader
  },

  mounted(){
    // this.modifyPhone()
  },
  methods:{
    nextStep(){
      this.modifyState=!this.modifyState;
    },
    // modifyPhone(){
    //   this.appFetch({
    //     url:'login',
    //     method:'post',
    //     data:{

    //     }
    //   })
    // }
  }

}
