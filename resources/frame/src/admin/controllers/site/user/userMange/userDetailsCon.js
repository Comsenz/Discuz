/*
* 用户详情
* */

import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';
import browserDb from '../../../../../helpers/webDbHelper';

export default {
  data:function () {
    return {
      fileList:[],
      options: [],
      optionsList:[],
      imageUrl: '',
      userRole: [],
      userInfo: {},
      newPassword:'',
      wechatNickName:'',
      sex:'',
      optionsStatus: [
        {
          value: false,
          label: '正常'
        },
        {
          value: true,
          label: '禁用'
        }
      ],
      value: '',
      query: {}
    }
  },

  created(){
    this.query = this.$route.query;
    this.getUserDetail();
    this.getUserList()
  },

  methods:{
    async getUserDetail(){
      try{
        const response = await this.appFetch({
          method: 'get',
          url: 'users',
          splice: `/${this.query.id}`,
          data:{
            include:'wechat,groups'
          }
        })
        if (response.errors){
          this.$message.error(response.errors[0].code);
        }else{
          console.log(response,'response');
          this.userInfo = response.readdata._data;
          this.imageUrl = this.userInfo.avatarUrl;
          this.userRole = response.readdata.groups.map((v)=>{
            return  v._data.id
           });
          //  console.log(this.userRole,'是我啊啊啊啊啊')
          //  console.log(this.options,'option')
          if(response.readdata.wechat){
            this.wechatNickName = response.readdata.wechat._data.nickname
            this.sex = response.readdata.wechat._data.sex
          }
          console.log()
        }

      } catch(err){
        console.error(err, 'getUserDetail')
      }
    },
    handleRemove(file, fileList) {
      console.log(file, fileList);
    },
    deleteImage(){
      this.imageUrl = '';
      this.appFetch({
        url:'deleteAvatar',
        method:'delete',
        splice: `/${this.query.id}`+'/avatar',
        data:{

        }
      })
    },
    handlePreview(file) {
      console.log(file);
    },
    handleExceed(files, fileList) {
      this.$message.warning(`当前限制选择 3 个文件，本次选择了 ${files.length} 个文件，共选择了 ${files.length + fileList.length} 个文件`);
    },
    beforeRemove(file, fileList) {
      return this.MessageBox.confirm(`确定移除 ${ file.name }？`);
    },
    handleAvatarSuccess(res, file) {
      // this.imageUrl = URL.createObjectURL(file.raw);
    },
    handleFile(){

    },
    beforeAvatarUpload(file) {
      const isJPG = file.type === 'image/jpeg';
      const isLt2M = file.size / 1024 / 1024 < 2;

      if (!isJPG) {
        this.$message.error('上传头像图片只能是 JPG 格式!');
      }
      if (!isLt2M) {
        this.$message.error('上传头像图片大小不能超过 2MB!');
      }
      if(isJPG && isLt2M == true){
      }
      return isJPG && isLt2M;
    },
    uploaderLogo(file){
      console.log(file,'000000000000000')
      let formData = new FormData()
        formData.append('avatar', file.file)
          console.log(formData)
          this.appFetch({
            url:'upload',
            method:'post',
            splice: `${this.query.id}`+'/avatar',
            data:formData
          }).then(res=>{
            if (res.errors){
              this.$message.error(res.errors[0].code);
            }else{
              console.log(res,'是我我我我哦我我')
              this.imageUrl = res.readdata._data.avatarUrl;
              
            }

          })
    },

    submission(){
      var reg = 11 && /^((13|14|15|17|18)[0-9]{1}\d{8})$/; //手机号正则验证
      var mobile = this.userInfo.originalMobile;
      if(mobile ==''){

      }else if(!reg.test(mobile)){
        return  this.$toast("您输入的手机号码不合法，请重新输入");
      }
      // if (!reg.test(mobile)) { //手机号不合法
      // return  this.$toast("您输入的手机号码不合法，请重新输入");
      // }
      this.appFetch({
        url:'users',
        method:'patch',
        splice:`/${this.query.id}`,
        data:{
          "data":{
            "attributes":{
              'newPassword':this.newPassword,
              'mobile':mobile,
              'groupId':this.userRole,
              'status':this.userInfo.status
            }
          }
        }
      }).then(res=>{
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else{
          console.log(res)
          this.$message({ message: '提交成功', type: 'success' });
        }

      })
    },

    async getUserList(){  //获取用户角色
      try{
        const response = await this.appFetch({
          method: 'get',
          url: 'groups'
        })
        const data = response.data;
        console.log(data,'8888')
        this.options = data.map((v)=>{
          return {
              value: v.id,
              label: v.attributes.name
          }
        })
      } catch(err){
        console.error(err, 'getUserList')
      }
    },

  },

  components:{
    Card,
    CardRow
  }
}
