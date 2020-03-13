/**
 * 发布主题控制器
 */
import { debounce, autoTextarea } from '../../../../../../common/textarea.js';
import appCommonH from '../../../../../../helpers/commonHelper';
import browserDb from '../../../../../../helpers/webDbHelper';
import axiosHelper from "axiosHelper";			
import TcVod from 'vod-js-sdk-v6';
let rootFontSize = parseFloat(document.documentElement.style.fontSize);
//获取签名
function getSignature() {
  // console.log('000000');
    return axiosHelper({
      url: 'signature',
      method: 'get', 
    }).then((res) => {
      // console.log(res.readdata._data.signature,'~~~+++++~~~~');
      return res.readdata._data.signature;
    })
}
export default {
  data:function () {
    return {
      headerTitle:"编辑主题",
      selectSort:'',
      showPopup:false,
      categories: [],
      categoriesId: [],
      oldCateId:'',
      cateId:'',
      content:'',
      showFacePanel: false,
      keyboard: false,
      keywordsMax: 1000,
      list: [],
      footMove: false,
      payMove: false,
      faceData:[],
      // fileListOne:[],
      // fileList: [
      //   // Uploader 根据文件后缀来判断是否为图片文件
      //   // 如果图片 URL 中不包含类型信息，可以添加 isImage 标记来声明
      //   // { url: 'https://cloud-image', isImage: true }
      // ],
      uploadShow:false,
      avatar: "",
      postsId:'',
      files: {
        name: "",
        type: ""
      },
      headerImage: null,
      picValue: null,
      upImgUrl:'',
      enclosureShow: false,
      isWeixin: false,
      isPhone: false,
      themeCon:false,
      attriAttachment:false,
      fileLength:0,
      canUploadImages:'',
      canUploadAttachments:'',
      supportImgExt: '',
      supportImgExtRes:'',
      supportFileExt:'',
      supportFileArr:'',
      limitMaxLength:true,
      limitMaxEncLength:true,
      // fileListOneLen:'',
      // enclosureListLen:'',
      isiOS: false,
      // encuploadShow: false,
      testingRes:false,
      backGo:-2,
      formdataList: [],
      viewportHeight: '',
      postFormScrollTop: '',
      nowCate: [],
      payValue: '免费',
      paySetShow: false,
      isCli: true,
      moneyVal: '',
      paySetValue: '',
      videoShow: false,   //上传视频后显示
      videoUp: false,      //上传加号
      vcVideoName: '',
      uploaderInfos: [],
      testingSizeRes: false,
      testingTypeRes: false,
      fileId: '',
      supportVideoExt: '',
      supportVideoExtRes:'',
      fileSize: '',
      vcVideoName: '',
      loading: false,
    }
  },

  mounted () {
    let postForm = document.getElementById('postForm');
    postForm.style.height = (this.viewportHeight) + 'px';

    let text = document.getElementById('post-topic-form-text');

    text.addEventListener("touchstart",(e)=>{
      // alert('触发');
      // this.showFacePanel = false;this.footMove = false;this.keyboard = false;

      let textarea = this.$refs.textarea;
      textarea.focus();
      let prevHeight = 300;
      textarea && autoTextarea(textarea, 5, 65535, (height) => {
        height += 20;
        if (height !== prevHeight) {
          prevHeight = height;
          let rem = height / rootFontSize;
          // this.$refs.list.style.height = `calc(100% - ${rem}rem)`;
        }
      });
    });


    this.$nextTick(() => {
        let textarea = this.$refs.textarea;
        textarea.focus();
        let prevHeight = 300;
        textarea && autoTextarea(textarea, 5, 65535, (height) => {
          height += 20;
          if (height !== prevHeight) {
            prevHeight = height;
            let rem = height / rootFontSize;
            // this.$refs.list.style.height = `calc(100% - ${rem}rem)`;
          }
        });
      })
      //设置在pc的宽度
      if(this.isWeixin != true && this.isPhone != true){
        this.limitWidth();
      }
  },
  computed: {
      themeId: function(){
          return this.$route.params.themeId;
      }
  },
  created(){

    this.tcVod = new TcVod({
      getSignature: getSignature
    });

    var videoExt = '';
    if(browserDb.getLItem('siteInfo')){
      this.fileSize = browserDb.getLItem('siteInfo')._data.qcloud.qcloud_vod_size;
      videoExt = browserDb.getLItem('siteInfo')._data.qcloud.qcloud_vod_ext.split(',');
      var videoStr='';
      var videoStrRes ='';
      for(var k=0;k<videoExt.length;k++){
        videoStr = '.'+videoExt[k]+',';
        videoStrRes = 'image/'+videoExt[k]+',';
        this.supportVideoExt += videoStr;
        this.supportVideoExtRes += videoStrRes;
      }
    } else{
      videoExt ='*';
    }
    this.viewportHeight = window.innerHeight;
    this.isWeixin = appCommonH.isWeixin().isWeixin;
    this.isPhone = appCommonH.isWeixin().isPhone;
    var u = navigator.userAgent;
    this.isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1; //android终端
    this.isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
    if(this.isiOS) {
      this.encuploadShow = true;
    }
    //初始化请求分类接口
    this.loadCategories();
    //初始化请求主题数据
    this.detailsLoad();
    this.getInfo();
  },
  watch: {
    showFacePanel: function(newVal,oldVal){
      this.showFacePanel = newVal;
      if(this.showFacePanel) {
        document.getElementById('postForm').style.height = (this.viewportHeight - 240) + 'px';
      } else {
        document.getElementById('postForm').style.height = '100%';
      }
    },
  },
  methods: {

    vExampleAdd: function() {
      this.$refs.vExampleFile.click();
    },
    //验证上传格式是否符合设置
    testingType(eFile,allUpext){
      let extName = eFile.name.substring(eFile.name.lastIndexOf(".")).toLowerCase();
      let AllUpExt = allUpext;
      if(AllUpExt.indexOf(extName + ",") == "-1"){
        this.$toast.fail("文件格式不正确!");
        this.testingTypeRes = false;
        // return false;
      } else {
        this.testingTypeRes = true;
      }
    },
    //验证上传文件大小是否符合设置
    testingSize(eFile,allowSize){
      // console.log(eFile,'上传的');
      let fileSize = eFile.size;
        // 视频大小大于接口返回的最大限制值时置空
        if (fileSize / 1024 / 1024 > allowSize) {
          this.$toast.fail('超出视频大小限制');
          // this.$refs.vExampleFile.files[0] = '';
          this.$refs.vExample.reset();
          
          this.testingSizeRes = false;
        } else {
          this.testingSizeRes = true;
        }
    },

    //上传视频
    vExampleUpload(e) {
      this.testingType(e.target.files[0],this.supportVideoExt);
      this.testingSize(e.target.files[0],this.fileSize);
      if(this.testingSizeRes && this.testingTypeRes){
        this.loading = true;
        var self = this;
        var mediaFile = this.$refs.vExampleFile.files[0];
        this.vcVideoName = this.$refs.vExampleFile.files[0].name;
        var uploader = this.tcVod.upload({
          mediaFile: mediaFile,
          // coverFile: coverFile,
        });
        uploader.on("media_progress", function(info) {
          uploaderInfo.progress = info.percent;
        });
        uploader.on("media_upload", function(info) {
          uploaderInfo.isVideoUploadSuccess = true;
        });

        // console.log(uploader, "uploader");

        var uploaderInfo = {
          videoInfo: uploader.videoInfo,
          isVideoUploadSuccess: false,
          isVideoUploadCancel: false,
          progress: 0,
          fileId: "",
          videoUrl: "",
          cancel: function() {
            uploaderInfo.isVideoUploadCancel = true;
            uploader.cancel();
          }
        };

        this.uploaderInfos.push(uploaderInfo);

        uploader.done().then((doneResult) => {
          // console.log("doneResult", doneResult);
          uploaderInfo.fileId = doneResult.fileId;
          this.videoUp = false;
          this.loading =false;
          this.videoShow = true;
          this.fileId = doneResult.fileId;
          // console.log('要提交的视频id',this.fileId);
        })
        .then(function(videoUrl) {
          uploaderInfo.videoUrl = videoUrl;
          self.$refs.vExample.reset();
        });
      }
      
    },
     //请求站点信息，用于判断是否能上传附件
    getInfo(){
      this.appFetch({
        url: 'forum',
        method: 'get',
        data: {
          include: ['users'],
        }
      }).then((res) => {
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        } else {
          var ImgExt = res.readdata._data.set_attach.support_img_ext.split(',');
          var ImgStr='';
          var imgStrRes ='';
          for(var k=0;k<ImgExt.length;k++){
            ImgStr = '.'+ImgExt[k]+',';
            imgStrRes = 'image/'+ImgExt[k]+',';
            this.supportImgExt += ImgStr;
            this.supportImgExtRes += imgStrRes;
          }

          var fileExt = res.readdata._data.set_attach.support_file_ext.split(',');
          var fileStr='';
          for(var k=0;k<fileExt.length;k++){
            fileStr = '.'+fileExt[k]+',';
            this.supportFileExt += fileStr;
          }
          this.canUploadImages = res.readdata._data.other.can_upload_images;
          this.canUploadAttachments = res.readdata._data.other.can_upload_attachments;
        }
      });
    },
    //初始化请求编辑主题数据
    detailsLoad(){
      this.appFetch({
        url: 'threads',
        method: 'get',
        splice:'/'+this.themeId,
        data: {
          include: ['firstPost',  'firstPost.images', 'firstPost.attachments', 'category', 'threadVideo'],
        }
      }).then((res) => {
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        } else {
          console.log(res,'resssss');
          this.oldCateId = res.readdata.category._data.id;
          this.selectSort = res.readdata.category._data.name;
          this.content = res.readdata.firstPost._data.content;
          this.postsId = res.readdata.firstPost._data.id;
          this.vcVideoName = res.readdata.threadVideo._data.file_name;
          this.fileId = res.readdata.threadVideo._data.file_id;
          if(this.vcVideoName != '' && this.vcVideoName != null){
            this.videoShow = true;
          }
        }
      })
    },
    //删除视频
    videoDeleClick(){
      this.videoShow = false;
      this.videoUp = true;
      this.fileId = '';
    },
    //发布主题
    publish(){
      if(this.oldCateId != this.cateId){
        this.appFetch({
          url:'threads',
          method:"patch",
          splice:'/'+this.themeId,
          data:{
            "data": {
              "type": "threads",
              "attributes": {
                "price": this.paySetValue,
                'file_id': this.fileId,
                'file_name': this.vcVideoName,
              },
              "relationships": {
                "category": {
                  "data": {
                    "type": "categories",
                    "id": this.cateId,
                  }
                }
              }
            }
          },
        }).then((res)=>{
          if (res.errors){
            this.$toast.fail(res.errors[0].code);
            throw new Error(res.error)
          } else {
            // this.$router.push({ path:'/details'+'/'+this.themeId});
          }
        })
      }
      this.appFetch({
        url:'posts',
        method:"patch",
        splice:'/'+this.postsId,
        data:{
          "data": {
            "type": "threads",
            "attributes": {
                "content": this.content,
                
            },

            "relationships": {
              "attachments": {
                "data":this.attriAttachment
              },
            }
          }
        },
      }).then((res)=>{
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        } else {
          this.$router.replace({ path:'/details'+'/'+this.themeId});
        }
      })
    },

    //上传之前先判断是否有权限上传图片
    beforeHandleFile(){
      if(!this.canUploadImages){
        this.$toast.fail('没有上传图片的权限');
      } else {
        if(!this.limitMaxLength){
          this.$toast.fail('已达上传图片上限');
        }
      }
    },

    beforeHandleEnclosure(){
      if(!this.canUploadAttachments){
        this.$toast.fail('没有上传附件的权限');
      } else {
        if(!this.limitMaxEncLength){
          this.$toast.fail('已达上传附件上限');
        }
      }
    },

    //输入框自适应高度
    clearKeywords () {
      this.keywords = '';
      this.list = [];
      let textarea = this.$refs.textarea;
      let height = 40;
      let rem = height / rootFontSize;
      textarea.style.height = `${rem}rem`;
      rem = (height + 20) / rootFontSize;
      // this.$refs.list.style.height = `calc(100% - ${rem}rem)`;
      textarea.focus();
    },
    handleFaceChoose (face) {
      const value = this.content;
      const el = this.$refs.textarea;
      const startPos = el.selectionStart;
      const endPos = el.selectionEnd;
      const newValue = value.substring(0, startPos) + face + value.substring(endPos, value.length)
      this.content = newValue
      if (el.setSelectionRange) {
        setTimeout(() => {
          const index = startPos + face.length
          el.setSelectionRange(index, index)
        }, 0)
      }
    },
    addExpression(){
      this.keyboard = !this.keyboard;
      this.appFetch({
        url: 'emojis',
        method: 'get',
        data: {
          include: '',
        }
      }).then((data) => {
        this.faceData = data.readdata;
      })
      this.showFacePanel = !this.showFacePanel;
      if(this.showFacePanel) {
        document.getElementById('postForm').style.height = (this.viewportHeight - 240) + 'px';
      } else {
        document.getElementById('postForm').style.height = '100%';
      }
      this.footMove = !this.footMove;
      this.payMove = !this.payMove;
    },
    backClick() {
      this.$router.go(-1);
    },
    dClick() {
      this.showPopup = true;
    },
    onConfirm( value, index) {
      var id = value.id;
      this.cateId = id;
      var text = value.text;
      this.showPopup = false;
      this.selectSort = value.text;
    },
    //分类接口
    loadCategories(){
      this.appFetch({
        url: 'categories',
        method: 'get',
        data: {
          include: '',
        }
      }).then((res) => {

        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        } else {
          var newCategories = [];
          newCategories = res.readdata;
          for(let j = 0,len=newCategories.length; j < len; j++) {
            this.categories.push(
              {
                'text': newCategories[j]._data.name,
                'id':newCategories[j]._data.id
              }
            );
            this.categoriesId.push(newCategories[j]._data.id);
          }
        }
      })
    },
    onCancel() {
      this.showPopup = false;
    },
    //设置付费金额,，显示弹框
    paySetting(){
      this.paySetShow = true;
      if(this.paySetShow) {
        setTimeout(function () {
          document.getElementById('payMoneyInp').focus();
        }, 200);
      }
    },
    //关闭付费设置弹框
    closePaySet(){
      this.paySetShow = false;
      this.paySetValue = '免费';
    },
    //设置付费时，实时获取输入框的值，用来判断按钮状态
    search: function (event) {
      // if(event.target.value != null && event.target.value > 0){
      //   this.isCli = true;
      // } else {
      //   this.isCli = false;
      // }
    },
    //点击确定按钮，提交付费设置
    paySetSure(){
      this.paySetShow = false;
      if(this.paySetValue <= 0){
        this.payValue = '免费';
      } else {
        this.payValue = this.paySetValue +'元';
      }
    },

  }
}
