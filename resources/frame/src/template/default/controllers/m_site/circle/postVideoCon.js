/**
 * 发布视频主题控制器
 */
import { debounce, autoTextarea } from '../../../../../common/textarea.js';
import appCommonH from '../../../../../helpers/commonHelper';
import browserDb from '../../../../../helpers/webDbHelper';
import axiosHelper from "axiosHelper";			
import TcVod from 'vod-js-sdk-v6';
let rootFontSize = parseFloat(document.documentElement.style.fontSize);
//获取签名
function getSignature() {
  console.log('000000');
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
      headerTitle:"发布视频",
      selectSort:'',
      showPopup:false,
      categories: [],
      categoriesId: [],
      cateId:'',
      content:'',
      showFacePanel: false,
      keyboard: false,
      // expressionShow: false,
      keywordsMax: 10000,
      list: [],
      footMove: false,
      payMove: false,
      faceData:[],
      // fileListOne:[],
      uploadShow:false,
      // enclosureList:[],
      avatar: "",
      themeId:'',
      postsId:'',
      files: {
        name: "",
        type: '',
      },
      headerImage: null,
      picValue: null,
      upImgUrl:'',
      // enclosureShow: false,
      isWeixin: false,
      isPhone: false,
      themeCon:false,
      attriAttachment:false,
      canUploadImages:'',
      canUploadAttachments:'',
      supportVideoExt: '',
      supportVideoExtRes:'',
      fileSize: '',
      // supportFileExt:'',
      // supportFileArr:'',
      limitMaxLength:true,
      limitMaxEncLength:true,
      // fileListOneLen:'',
      // enclosureListLen:'',
      isiOS: false,
      encuploadShow: false,
      testingRes:false,
      backGo:-2,
      formdataList:[],
      viewportWidth: '',
      viewportHeight: '',
      nowCate: [],
      payValue: '免费',
      paySetShow: false,
      isCli: true,
      moneyVal: '',
      paySetValue: '',
      videoShow: false,   //上传视频后显示
      videoUp: true,      //上传加号
      vcVideoName: '',
      uploaderInfos: [],
      testingRes: false,
      fileId: '',

    }
  },
  computed: {
    nowCateId: function () {
      return this.$route.params.cateId;
    }
  },
  mounted () {
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
  created(){
    this.tcVod = new TcVod({
      getSignature: getSignature
    });
    // console.log(this.tcVod,'343423');
    // this.cateId = this.$route.query.cateId;
    var videoExt = '';
    if(browserDb.getLItem('siteInfo')){
      console.log(browserDb.getLItem('siteInfo'),'缓存');
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
    this.viewportWidth = window.innerWidth;
    this.viewportHeight = window.innerHeight;
    this.isWeixin = appCommonH.isWeixin().isWeixin;
    this.isPhone = appCommonH.isWeixin().isPhone;
    var u = navigator.userAgent;
    this.isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1; //android终端
    this.isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
    if(this.isiOS) {
      this.encuploadShow = true;
    }
    if(this.$route.params.themeId){
      var themeId = this.$route.params.themeId;
      var postsId = this.$route.params.postsId;
      var themeContent = this.$route.params.themeContent;
      this.themeId = themeId;
      this.postsId = postsId;
      this.content = themeContent;
    }
    //初始化请求分类接口
    this.loadCategories();
    //初始化请求主题数据
    // this.detailsLoad();
    this.getInfo();


  },
  watch: {
    // 'fileListOne.length': function(newVal,oldVal){
    //   this.fileListOneLen = newVal;
    //   if(this.fileListOneLen >= 12){
    //     this.limitMaxLength = false;
    //   } else {
    //     this.limitMaxLength = true;
    //   }
    // },
    // 'limitMaxLength': function(newVal,oldVal){
    //   this.limitMaxLength = newVal;
    // },
    // 'enclosureList.length': function(newVal,oldVal){
    //   this.enclosureListLen = newVal;
    //   if(this.enclosureListLen >= 3){
    //     this.limitMaxEncLength = false;
    //   } else {
    //     this.limitMaxEncLength = true;
    //   }
    // },
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
      // this.$refs.vcExampleCover.click();
    },
    //添加封面
    // vcExampleAddCover: function() {
    //   this.$refs.vcExampleCover.click();
    // },
    //验证上传格式是否符合设置
    testingType(eFile,allUpext){
      let extName = eFile.name.substring(eFile.name.lastIndexOf(".")).toLowerCase();
      let AllUpExt = allUpext;
      if(AllUpExt.indexOf(extName + ",") == "-1"){
        this.$toast.fail("文件格式不正确!");
        this.testingRes = false;
        // return false;
      } else {
        this.testingRes = true;
      }
    },
    //验证上传文件大小是否符合设置
    testingSize(eFile,allowSize){
      console.log(eFile,'上传的');
      let fileSize = eFile.size;
        // 视频大小大于接口返回的最大限制值时置空
        if (fileSize / 1024 / 1024 > allowSize) {
          this.$toast.fail('超出视频大小限制');
          // this.$refs.vExampleFile.files[0] = '';
          this.$refs.vExample.reset();
          
          this.testingRes = false;
        } else {
          this.testingRes = true;
        }
    },

    //上传视频
    vExampleUpload(e) {
      this.testingType(e.target.files[0],this.supportVideoExt);
      this.testingSize(e.target.files[0],this.fileSize);
      if(this.testingRes){
        var self = this;
        var mediaFile = this.$refs.vExampleFile.files[0];
        this.vcVideoName = this.$refs.vExampleFile.files[0].name;
        console.log(mediaFile,'mediaFile');
        // var coverFile = this.$refs.vcExampleCover.files[0];
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

        console.log(uploader, "uploader");

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

        uploader
        .done()
        .then((doneResult) => {
          console.log("doneResult", doneResult);
          uploaderInfo.fileId = doneResult.fileId;
          this.videoUp = false;
          this.videoShow = true;
          this.fileId = doneResult.fileId;
          console.log('要提交的视频id',this.fileId);
          // return getAntiLeechUrl(doneResult.video.url);
        })
        .then(function(videoUrl) {
          uploaderInfo.videoUrl = videoUrl;
          self.$refs.vExample.reset();
        });
      }
      
    },

    setVcExampleCoverName: function() {
      this.vcExampleCoverName = this.$refs.vcExampleCover.files[0].name;
    },
    getInfo(){
      //请求站点信息，用于判断是否能上传附件
      this.appFetch({
        url: 'forum',
        method: 'get',
        data: {
          include: ['users'],
        },
      }).then((res) => {
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        } else {

          this.canUploadImages = res.readdata._data.other.can_upload_images;
          this.canUploadAttachments = res.readdata._data.other.can_upload_attachments;
        }
      });
    },
    
    //删除视频
    videoDeleClick(){
      this.videoShow = false;
      this.videoUp = true;
      this.fileId = '';
    },
    //发布主题
    publish(){
      if(this.content == '' || this.content == null){
        this.$toast.fail('内容不能为空');
        return;
      }
      if(this.cateId == 0 || this.cateId == undefined){
        this.$toast.fail('请选择分类');
        return;
      }
      if(this.postsId && this.content){
        let posts = 'posts/'+this.postsId;
        this.appFetch({
          url:posts,
          method:"patch",
          data: {
            "data": {
              "type": "posts",
              "attributes": {
                "content": this.content
              }
            }
          }
        }).then((res)=>{
          if (res.errors){
            if (res.errors[0].detail){
              this.$toast.fail(res.errors[0].code + '\n' + res.errors[0].detail[0])
            } else {
              this.$toast.fail(res.errors[0].code);
            }
          } else {
            console.log('主题');
            this.$router.replace({ path:'details'+'/'+this.themeId,query:{backGo:this.backGo},replace:true});
          }
        })
      } else {
        this.appFetch({
          url:"threads",
          method:"post",
          data:{
            "data": {
              "type": "threads",
              "attributes": {
                  "content": this.content,
                  'file_id': this.fileId,
                  'type': 2,
              },
              "relationships": {
                  "category": {
                      "data": {
                          "type": "categories",
                          "id": this.cateId
                      }
                  },
                  "attachments": {
                    "data":this.attriAttachment
                  },
              }

            }
          },
        }).then((res)=>{
          if (res.errors){
            if (res.errors[0].detail){
              this.$toast.fail(res.errors[0].code + '\n' + res.errors[0].detail[0])
            } else {
              this.$toast.fail(res.errors[0].code);
            }
          } else{
            var postThemeId = res.readdata._data.id;
            var _this = this;
            console.log('视频');
            _this.$router.replace({ path:'/details'+'/'+postThemeId,query:{backGo:this.backGo},replace:true});
          }
        })
      }
    },

    //设置底部在pc里的宽度
    limitWidth(){
      document.getElementById('post-topic-footer').style.width = "640px";
      let viewportWidth = window.innerWidth;
      document.getElementById('post-topic-footer').style.left = (viewportWidth - 640)/2+'px';
    },

    getAllEvens(arr){
      arr => {
        let temp = evens(arr);
        return flat(temp);
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
    searchChange: debounce(function () {
      let trim = this.keywords && this.keywords.trim();
      if (!trim) {
        this.list = [];
        return;
      }
      const params = {
        keywords: this.keywords
      }
      // 调api ...
    }),
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
    // handleKeyboardClick () {
    //   this.showFacePanel = false;
    //   this.$refs.textarea.focus();
    //   this.footMove = false;
    // },
    //表情
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
      // if(this.showFacePanel == true){
      //   // document.getElementById('showFacePanel').style.width = "640px";
      //   document.getElementById('showFacePanel').style.left = (this.viewportWidth - 640)/2+'px';
      // }
      if(this.showFacePanel) {
        document.getElementById('postForm').style.height = (this.viewportHeight - 240) + 'px';
      } else {
        document.getElementById('postForm').style.height = '100%';
      }
      this.footMove = !this.footMove;
    },
    backClick() {
      this.$router.go(-1);
    },
    dClick() {
      this.showPopup = true;
    },
    onConfirm( value, index) {
      // console.log(value,'====================');
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
          if(this.nowCateId != 0 && this.nowCateId != undefined ){
            var nowCate = {};
            nowCate = newCategories.find((item) => {
              if(item._data.id === this.nowCateId){
                return item
              }
            })
            this.nowCate = {id:nowCate._data.id,name:nowCate._data.name};
            this.cateId = this.nowCate.id;
            this.selectSort = this.nowCate.name;
          } else {
            this.selectSort = "选择分类";
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

  },
  beforeRouteEnter(to,from,next){
    next();
    /*next(vm =>{
      if (from.name === 'circle'){
        vm.backGo = -2
      }
    });*/
  }
}
