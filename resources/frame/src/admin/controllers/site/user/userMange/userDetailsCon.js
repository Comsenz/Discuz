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
      imageUrl: '',
      userInfo: {},
      newPassword:'',
      wechatNickName:'',
      sex:'',
      options: [
        {
          value: 0,
          label: '正常'
        }, 
        {
          value: 1,
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
  },

  methods:{
    async getUserDetail(){
      try{
        const response = await this.appFetch({
          method: 'get',
          url: 'users',
          splice: `/${this.query.id}`,
          data:{
            include:'wechat'
          }
        })
        console.log(response,'response');
        this.userInfo = response.readdata._data;
        this.imageUrl = this.userInfo.avatarUrl;
        if(response.readdata.wechat){
          this.wechatNickName = response.readdata.wechat._data.nickname
          this.sex = response.readdata.wechat._data.sex
        }
        console.log()
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
            this.imageUrl = res.readdata._data.avatarUrl;
          })
    },
    
    submission(){
      const userId = browserDb.getLItem('tokenId');
      this.appFetch({
        url:'users',
        method:'patch',
        splice:'/'+userId,
        data:{
          'newPassword':this.newPassword,
          'mobile':this.userInfo.mobile,
          'status':this.userInfo.status
        }
      }).then(res=>{
        console.log(res)
      })
    },

  },

  components:{
    Card,
    CardRow
  }
}
