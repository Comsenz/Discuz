
import Card from '../../../view/site/common/card/card';
import CardRow from '../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      radio:'1',
      radio2:'2',
      fileList:[],
      imageUrl: '',
      loading: true,
      fullscreenLoading: false,
      siteName:'aaa',
      siteIntroduction:'站点介绍站点介绍站点介绍站点介绍站点介绍',
      siteMode:'1', //站点模式选择
      sitePrice:'',
      siteExpire:'',
      siteAuthorScale:'',
      siteMasterScale:'',
      siteClose:'1',  //关闭站点选择
      siteLogoFile: {},
      siteRecord:'',
      siteStat:'',
      siteCloseMsg:''

    }
  },
  methods:{
    radioChange(siteMode){
      this.siteMode = siteMode;
      console.log(this.radio);
    },

    handleRemove(file, fileList) {
      console.log(file, fileList);
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
      this.imageUrl = URL.createObjectURL(file.raw);
    },
    beforeAvatarUpload(file) {
      this.siteLogoFile = file;
      // console.log(file);
      let _this = this;
      this.siteLogoFile.splice(0, 1);
      setTimeout(function(){
          _this.siteLogoFile = [{name: file.name, file: file, url:''}];
      }, 1500);
      // return false;
      let formData = new FormData();
      formData.append('logo',file);
      this.appFetch({
        url:'logo',
        method:'post',
        data:formData,
      }).then(data=>{
        console.log(data)
        this.$message('提交成功');
      }).catch(error=>{
        console.log('失败');
      })
      const isJPG = file.type === 'image/jpeg';
      const isLt2M = file.size / 1024 / 1024 < 2;

      if (!isJPG) {
        this.$message.error('上传头像图片只能是 JPG 格式!');
      }
      if (!isLt2M) {
        this.$message.error('上传头像图片大小不能超过 2MB!');
      }
      return isJPG && isLt2M;
    },
    siteSetPost(){
      this.appFetch({
        url:'settings',
        method:'post',
        data:{
          "data":[
            {
             "attributes":{
              "key":"site_name",
              "value":this.siteName,
              "tag": "default"
             }
            },
            {
             "attributes":{
              "key":"site_introduction",
              "value":this.siteIntroduction,
              "tag": "default"
             }
            },
            {
             "attributes":{
              "key":"site_modea",
              "value":this.siteMode,
              "tag": "default"
             }
            },
            {
             "attributes":{
              "key":"site_price",
              "value":this.sitePrice,
              "tag": "default"
             }
            },
            {
             "attributes":{
              "key":"site_expire",
              "value":this.siteExpire,
              "tag": "default"
             }
            },
            {
             "attributes":{
              "key":"site_author_scale",
              "value":this.siteAuthorScale,
              "tag": "default"
             }
            },
            {
             "attributes":{
              "key":"site_master_scale",
              "value":this.siteMasterScale,
              "tag": "default"
             }
            },
            {
             "attributes":{
              "key":"site_record",
              "value":this.siteRecord,
              "tag": "default"
             }
            },
            {
             "attributes":{
              "key":"site_stat",
              "value":this.siteStat,
              "tag": "default"
             }
            },
            {
             "attributes":{
              "key":"site_close",
              "value":this.siteClose,
              "tag": "default"
             }
            },
            {
             "attributes":{
              "key":"site_close_msg",
              "value":this.siteCloseMsg,
              "tag": "default"
             }
            }
           ]

        }
      }).then(data=>{
        console.log(data)
        this.$message({
          message: '提交成功',
          type: 'success'
        });
      }).catch(error=>{
        console.log('失败');
      })
    }

  },
  components:{
    Card,
    CardRow
  }
}
