/**
 * 修改资料
 */


import ModifyHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import browserDb from '../../../../../helpers/webDbHelper';


export default {
  data:function () {
    return {
      headPortrait:'',//头像
      modifyPhone:'', //修改手机号
      changePwd:'',//修改密码
      bindType:'',//绑定类型
      
    }
  },

  components:{
    ModifyHeader
  },
  created(){
    this.modifyData() //修改资料
  },
  methods:{
    myModify(str){
      switch (str) {
        case 'modify-phone':
          this.$router.push('/modify-phone'); //修改手机号
          break;
        case 'change-pwd':
          this.$router.push('/change-pwd'); //修改密码
          break;
        default:
          this.$router.push('/');
      }
    },

    modifyData(){
      let userId = browserDb.getLItem('tokenId');
      this.apiStore.find('users',userId).then(res=>{
        this.modifyPhone = res.data.attributes.mobile;
        // this.headPortrait = res.data.attributes.avatarUr; //接口数据里头像为空
        this.headPortrait = "../../../../../../../static/images/mytx.png";
        
      })
    },
      handleFile: function (e) {
        // let $target = e.target || e.srcElement
        // let file = $target.files[0]
        // console.log(file)
        // var reader = new FileReader()
        // reader.onload = (data) => {
        //   let res = data.target || data.srcElement
        //   this.headPortrait = res.result
        // }
        // reader.readAsDataURL(file)
        // console.log(this.headPortrait)
        let file = e.target.files[0];
        console.log(file);

        // 获取file
      // 实例化
        let formdata = new FormData()
        formdata.append('avatar', file)
        console.log(formdata)



      let userId = browserDb.getLItem('tokenId');
      this.appFetch({
        url:'upload',
        method:'post',
        splice:userId+'/avatar',
        data:formdata
      }).then(res=>{
        this.headPortrait = res.data.attributes.avatarUrl;
      })
      },
     
 
  }
}
