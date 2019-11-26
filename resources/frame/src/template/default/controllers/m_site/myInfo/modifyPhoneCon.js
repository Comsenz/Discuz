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
      modifyState:true,
      bind:'找回密码'
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
    // modifyPhone(){      //修改手机号
    //   this.appFetch({
    //     url:'sendSms',
    //     method:'post',
    //     data:{
    //       "data": {
    //         "attributes": {
    //           mobile:this.phone,
    //           type:this.bind
    //         }
    //       }

    //     }
    //   })
    // }
  }

}
