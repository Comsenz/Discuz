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
      wechatId:'',//id
      wechatNickname:''
      
    }
  },

  components:{
    ModifyHeader
  },
  created(){
    this.modifyData() //修改资料
    this.wechat()
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
      this.appFetch({
        url:'users',
        method:'get',
        splice:'/'+userId,
        data:{
          include:'wechat'
        }
      }).then(res=>{
        console.log(res)
        this.modifyPhone = res.readdata._data.mobile; //用户手机号
        this.headPortrait = res.readdata._data.avatarUrl; //用户头像
        this.wechatId = res.readdata._data.id;            //用户Id
        // this.wechatNickname = res.readdata.wechat._data.nickname //微信昵称
      })
    },
      handleFile: function (e) {
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

      myModifyWechat(){
        this.$dialog.confirm({
          title: '确认解绑微信',
          // message: '弹窗内容'
        }).then(() => {
          console.log('000000')
          this.wechat(this.wechatId)

        }).catch(() => {
          // on cancel
        });
      },
      wechat(id){
        if(id!= '' && id!= null){
          this.appFetch({
            url:'wechat',
            method:'delete',
            splice:this.wechatId+'/'+'wechat',
            data:{
            }
          })
        }    
      },
      wechatBind(){
        this.appFetch({
          url:'wechatBind',
          method:'patch',
          data:{}
        }).then(res=>{
          console.log(res)
        })
      }
     
  }
}
