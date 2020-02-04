
import Card from '../../../view/site/common/card/card';
import CardRow from '../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      radio:'1',
      radio2:'2',
      // fileList:[],
      imageUrl: '',
      imgWidht: 0,
      imgHeight: 0,
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
      // siteLogoFile: {},
      siteLogoFile: [],
      siteMasterId:'',
      siteRecord:'',
      siteStat:'',
      siteCloseMsg:'',
      dialogImageUrl: '',
      dialogVisible: false,
      fileList:[],
    }
  },

  created:function(){
    //初始化请求设置
    this.loadStatus();
  },
  computed: {
      // uploadDisabled:function() {
      //     return this.fileList.length >0
      // },

  },
  // watch: {
    //监听图片数组变化
  //   'this.fileList.length': {
  //     handler(newValue, oldValue) {
  //       if (newValue !== oldValue) {
  //         // 操作
  //          this.fileList = newValue;
  //         }
  //     }
  //   },
  // },

  methods:{
    loadStatus(){
      //初始化设置
      this.appFetch({
        url:'forum',
        method:'get',
        data:{
        }
      }).then(data=>{
        if (data.errors){
          this.$message.error(data.errors[0].code);
        }else {
          console.log(data);
          console.log('123');
          this.siteName = data.readdata._data.set_site.site_name;
          this.siteIntroduction = data.readdata._data.set_site.site_introduction;
          this.siteMode = data.readdata._data.set_site.site_mode;
          this.imageUrl = data.readdata._data.set_site.site_logo;
          this.getScaleImgSize(this.imageUrl,{width: 120, height: 120}).then((res)=>{
            this.imgWidht = res.width;
            this.imgHeight = res.height;
          })
          if (this.siteMode == 'pay') {
            this.radio = '2';
          } else {
            this.radio = '1';
          }
          this.sitePrice = data.readdata._data.set_site.site_price;
          this.siteExpire = data.readdata._data.set_site.site_expire;
          this.siteAuthorScale = data.readdata._data.set_site.site_author_scale;
          this.siteMasterScale = data.readdata._data.set_site.site_master_scale;
          // this.siteLogoFile = data.readdata._data.siteLogoFile;
          this.siteRecord = data.readdata._data.set_site.site_icp;
          this.siteStat = data.readdata._data.set_site.site_stat;
          this.siteClose = data.readdata._data.set_site.site_close;
          this.siteMasterId = data.readdata._data.set_site.site_author.id;
          // if (data.readdata._data.logo) {
          //   this.fileList.push({url: data.readdata._data.logo});
          // }
          if (this.siteClose == true) {
            this.radio2 = '1';
          } else {
            this.radio2 = '2';
          }
          this.siteCloseMsg = data.readdata._data.set_site.site_close_msg;

          // this.$message({'修改成功'});
        }
      }).catch(error=>{
        // console.log('失败');
      })
    },
    //删除已上传logo
    deleteImage(file, fileList) {
      // console.log(file);
      let logoFormData = new FormData()
      logoFormData.append('logo', file.raw);
      // this.uploaderLogo(logoFormData);
      this.appFetch({
        url:'logo',
        method:'delete',
        data:logoFormData,
      }).then(data=>{
        if (data.errors){
          this.$message.error(data.errors[0].code);
        }else {
          this.$message('删除成功');
        }
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
      // console.log(this.radio);
    },
    radioChangeClose(closeVal){
      if(closeVal == '1'){
        this.siteClose = true;
      } else {
        this.siteClose = false;
      }
    },
    handleAvatarSuccess(res, file) {
      // this.imageUrl = URL.createObjectURL(file.raw);
    },
    handleFile(){

    },
    getScaleImgSize(url, obj) {    //处理等比例上传图片，
      return new Promise((resolve, reject) => {
        this.getImageSize(url).then((res) => {
          const scale = res.height / res.width;
          if (scale > obj.height / obj.width) {
            resolve({
              width: obj.height / scale,
              height: obj.height
            })
          } else {
            resolve({
              width: obj.width,
              height: obj.width * scale
            })
          }

        }).catch((err) => {
          reject(err);
        })
      })
    },
    getImageSize(url){
      const img = document.createElement('img');

      return new Promise((resolve, reject) => {
        img.onload = ev => {
          resolve({ width: img.naturalWidth, height: img.naturalHeight });
        };
        img.src = url;
        img.onerror = reject;

      });
    },

    //上传时，判断文件的类型及大小是否符合规则
    beforeAvatarUpload(file) {
      const isJPG = file.type == 'image/jpeg' || file.type == 'image/png' || file.type == 'image/gif'
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
        if (data.errors){
          this.$message.error(data.errors[0].code);
        }else {
          this.imageUrl = data.readdata._data.default.logo;
          this.getScaleImgSize(this.imageUrl,{width: 120, height: 120}).then((res)=>{
            this.imgWidht = res.width;
            this.imgHeight = res.height;
          })
          console.log(this.imageUrl)
          this.$message({message: '上传成功', type: 'success'});
        }
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
              "value":this.siteName?this.siteName:'',
              "tag": "default"
             }
            },
            {
             "attributes":{
              "key":"site_introduction",
              "value":this.siteIntroduction?this.siteIntroduction:'',
              "tag": "default"
             }
            },
            {
             "attributes":{
              "key":"site_author",
              "value":this.siteMasterId,
              "tag": "default"
             }
            },
            {
             "attributes":{
              "key":"site_mode",
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
        if (data.errors){
          this.$message.error(data.errors[0].code);
        }else {
          this.$message({
            message: '提交成功',
            type: 'success'
          });
        }
      }).catch(error=>{
        console.log('失败');
      })
    },
    onblurFun(){
      if(this.siteAuthorScale == null || this.siteAuthorScale == ''){
        this.siteAuthorScale = 0;
      }
      if(this.siteMasterScale == null || this.siteMasterScale == ''){
        this.siteMasterScale = 0;
      }
      var countRes = parseFloat(this.siteAuthorScale) + parseFloat(this.siteMasterScale);
      if(countRes != 10){
        this.$message({
          message: '分成比例相加必须为10',
          type: 'error'
        });
      }
    },

  },
  components:{
    Card,
    CardRow
  }
}
