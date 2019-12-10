
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
    this.signUpSet()//获取前台信息
  },
  methods:{
    signUpSet(){
      this.appFetch({
        url:'forum',
        method:'get',
        data:{

        }
      }).then(res=>{
        this.pwdLength = res.readdata._data.passwordLength
        console.log(res)
      })
    },
    submission(){ //提交注册信息接口
      var reg = /^\d+$|^\d+[.]?\d+$/;
      var pwdLength = this.pwdLength;
      if(pwdLength === ''){
        return
      }
      if (!reg.test(pwdLength)) { //密码只能输入数字
        this.$message("密码只能输入数字");
        return
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
        this.$message('提交成功');
      })
      
    }
  },
  components:{
    Card,
    CardRow
  }
}
