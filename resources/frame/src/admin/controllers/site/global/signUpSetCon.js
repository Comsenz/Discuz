
import Card from '../../../view/site/common/card/card';
import CardRow from '../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      checked:true,
      pwdLength:'',   //密码长度
      checkList:[],   //密码规则
    }
  },
  created(){

  },
  methods:{
    submission(){ //提交注册信息接口
      var reg = /^\d+$|^\d+[.]?\d+$/;
      var pwdLength = this.pwdLength;
      if (!reg.test(pwdLength)) { //密码只能输入数字
        this.$toast("您输入的密码格式错误，请重新输入");
      }
      this.appFetch({
        url:'settings',
        method:'post',
        data:{
          "allow_register": this.checked,
          "password_length":this.pwdLength,
          
        }
      }).then(data=>{
        console.log(data)
      })
    }
  },
  components:{
    Card,
    CardRow
  }
}
