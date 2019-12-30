
import BindPhoneHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader'
import BindPhoneFooter from '../../../view/m_site/common/loginSignUpFooter/loginSignUpFooter'
import webDb from '../../../../../helpers/webDbHelper';
import appFetch from "../../../../../helpers/axiosHelper";

export default {
  data:function () {
    return {
      phoneNum:"",    //手机号
      verifyNum:"",              //验证码
      btnContent:"获取验证码",     //获取验证码按钮内文字
      time:1,                    //发送验证码间隔时间
      disabled:false,            //按钮状态
      login:'login',
      insterVal:'',
      isGray: false,
      siteMode:'',               //站点模式
    }
  },

  methods:{
    //获取验证码
    sendSmsCode(){
      var reg=11&& /^((13|14|15|17|18)[0-9]{1}\d{8})$/;//手机号正则验证
      var phoneNum = this.phoneNum;
      if(!phoneNum){//未输入手机号
       this.$toast("请输入手机号码");
       return;
      }
      if(!reg.test(phoneNum)){//手机号不合法
       this.$toast("您输入的手机号码不合法，请重新输入");
      }

      // 获取验证码请求
      this.appFetch({
        url:"sendSms",
        method:"post",
        data:{
          "data": {
            "attributes": {
              mobile:this.phoneNum,
              type:'bind'
            }
          }
        }
      }).then(res => {
          // console.log(res);
          this.insterVal = res.data.attributes.interval;
          // console.log(this.insterVal+'555555');
          this.time = this.insterVal;
          this.timer();
       });
    },
    timer(){
      // alert('执行');
      if(this.time>1){
        // alert('2222');
       this.time--;
       this.btnContent = this.time+"s后重新获取";
       this.disabled = true;
       var timer = setTimeout(this.timer,1000);
       this.isGray = true;
      }else if(this.time == 1){
       this.btnContent = "获取验证码";
       clearTimeout(timer);
       this.disabled = false;
       this.isGray = false;
      }
    },
    // 验证验证码
    // verificationCode(){
    //   var phoneNum = this.phoneNum;//手机号
    //   var verifyNum = this.verifyNum;//验证码
    //   var url = 'http://bosstan.asuscomm.com/api/common/verificationCode';
    //   this.$http.post(url,{
    //    username:phoneNum,
    //    code:verifyNum
    //   },{
    //    emulateJSON:true
    //   }).then((response)=>{
    //    console.log(response.body);
    //   });
    // },


    //绑定手机号
    bindPhone(){
      this.appFetch({
        url:"smsVerify",
        method:"post",
        data:{
          "data": {
            "attributes": {
              "mobile": this.phoneNum,
              "code": this.verifyNum,
              "type": 'bind'
            }
          }
        }
      }).then(res => {
          // console.log(res);
          this.$router.push({
            path:'/circle',
          });

        if(!res.errors){
          if(this.siteMode === 'pay'){
            this.$router.push({path:'pay-the-fee'});
          }else if(this.siteMode === 'public'){
            this.$router.push({path:'/'});
          }
        }

       }).catch(err=>{
        console.log(err);
      })
    },
    getUsers(id){
      return appFetch({
        url:'users',
        method:'get',
        splice:'/' + id,
        headers:{'Authorization': 'Bearer ' + webDb.getLItem('Authorization')},
        data:{
          include:['groups']
        }
      }).then(res=>{
        console.log(res);
        return res.readdata._data.mobile;
      }).catch(err=>{
        console.log(err);
      })
    },
  },

  created(){
    // console.log(this.time);
    this.siteMode = webDb.getLItem('siteInfo')._data.setsite.site_mode;
    let tokenId = webDb.getLItem('tokenId');
    this.getUsers(tokenId).then((res)=>{
      if (res !== ''){
        this.$router.push('bind-new-phone');
      }
    })
  },

  components:{
    BindPhoneHeader,
    BindPhoneFooter
  }
}
