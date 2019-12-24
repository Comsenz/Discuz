
import Card from '../../../view/site/common/card/card';
import CardRow from '../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      checked:'',
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
      
        // this.pwdLength = res.readdata._data.setreg.password_length
        this.checked = res.readdata._data.setreg.register_close
        this.pwdLength = res.readdata._data.setreg.password_length
        this.checkList = res.readdata._data.setreg.password_strength.split(',')
        console.log(this.checkList)
        console.log( res)
      })
    },
    submission(){ //提交注册信息接口
      var reg = /^\d+$|^\d+[.]?\d+$/;
      var pwdLength = this.pwdLength;
      var passwordStrength = this.checkList.join(",")
      // if(pwdLength === ''){
      //   return
      // }
      // if (!reg.test(pwdLength)) { //密码只能输入数字
      //   this.$message("密码只能输入数字");
      //   return
      // }
      this.appFetch({
        url:'settings',
        method:'post',
        data:{
          "data":[
            {
             "attributes":{
              "key":'register_close',
              "value":this.checked,
              "tag": 'default'
             }
            },{
              "attributes":{
                "key":'password_length',
                "value":this.pwdLength,
                "tag": 'default'
               }
            },{
              "attributes":{
                "key":'password_strength',
                "value":passwordStrength,
                "tag": 'default'
               }
            }
           ]
          // "register_close": this.checked,
          // "password_length":this.pwdLength,
          
        }
      }).then(data=>{
        console.log(data)
        this.$message({ message: '提交成功', type: 'success' });
      })
      
    }
  },
  components:{
    Card,
    CardRow
  }
}
