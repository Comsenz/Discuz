
import Card from '../../../view/site/common/card/card';
import CardRow from '../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      radio:'1',
      radio2:'2',
      // fileList:[],
      imageUrl: '',
      loading: true,
      fullscreenLoading: false,
      siteName:'',
      siteIntroduction:'',
      siteMode:'1', //站点模式选择
      sitePrice:'',
      siteExpire:'',
      siteAuthorScale:'',
      siteMasterScale:'',
      siteClose:'1',  //关闭站点选择
      siteLogoFile: {},
      siteLogoFile: [],
      siteRecord:'',
      siteStat:'',
      siteCloseMsg:'',
      dialogImageUrl: '',
      dialogVisible: false,
      fileList:[]
    }
  },

  created:function(){
    //初始化请求设置
    this.loadStatus();
  },
  methods:{
    loadStatus(){
      //初始化设置
      this.appFetch({
        url:'forum',
        method:'get',
        data:{
        }
      }).then(data=>{
        // console.log(data.readdata._data);
        this.siteName = data.readdata._data.siteName;
        this.siteIntroduction = data.readdata._data.siteIntroduction;
        this.siteMode = data.readdata._data.siteMode;
        if(this.siteMode == 'pay'){
          this.radio = '2';
        } else {
          this.radio = '1';
        }
        this.sitePrice = data.readdata._data.sitePrice;
        this.siteExpire = data.readdata._data.siteExpire;
        this.siteAuthorScale = data.readdata._data.siteAuthorScale;
        this.siteMasterScale = data.readdata._data.siteMasterScale;
        this.siteLogoFile = data.readdata._data.siteLogoFile;
        this.siteRecord = data.readdata._data.siteRecord;
        this.siteStat = data.readdata._data.siteStat;
        this.siteCloseMsg = data.readdata._data.siteCloseMsg;
        // this.$message({'修改成功'});
      }).catch(error=>{
        // console.log('失败');
      })
    },
    //删除已上传logo
    handleRemove(file, fileList) {
      // console.log(file);
      let logoFormData = new FormData()
      logoFormData.append('logo', file.raw);
      // this.uploaderLogo(logoFormData);
      this.appFetch({
        url:'logo',
        method:'delete',
        data:logoFormData,
      }).then(data=>{
        this.$message('删除成功');
      }).catch(error=>{
        console.log('上传失败');
      })
      // console.log(file, fileList);
    },
    handlePictureCardPreview(file) {
      this.dialogImageUrl = file.url;
      this.dialogVisible = true;
    },
    radioChange(siteMode){
      this.siteMode = siteMode;
      console.log(this.radio);
    },

    //上传时，判断文件的类型及大小是否符合规则
　　beforeAvatarUpload(file) {
　　　　const isJPG =file.type == 'image/jpeg' || file.type == 'image/png' || file.type == 'image/gif'
　　　　const isLt2M = file.size / 1024 / 1024 < 2
　　　　if (!isJPG) {
　　　　　　this.$message.warning('上传头像图片只能是 JPG/PNG/GIF 格式!')
　　　　　　return isJPG
　　　　}
　　　　if (!isLt2M) {
　　　　　　this.$message.warning('上传头像图片大小不能超过 2MB!')
　　　　　　return isLt2M
　　　　}
　　　　this.multfileImg = file
　　　　return isJPG && isLt2M
　　　},
    uploaderLogo(e) {
      console.log(e);
      let logoFormData = new FormData()
      logoFormData.append('logo', e.file);
      console.log(logoFormData);
      // this.uploaderLogo(logoFormData);
      this.appFetch({
        url:'logo',
        method:'post',
        data:logoFormData,
      }).then(data=>{
        // this.$message('上传成功');
      }).catch(error=>{
        console.log('上传失败');
      })
    },
    errorFile(){
      console.log(this.fileList);
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
