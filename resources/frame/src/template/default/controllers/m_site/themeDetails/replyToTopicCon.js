/*
* 回复主题控制器
* */

import {Bus} from '../../../store/bus.js';
import { debounce, autoTextarea } from '../../../../../common/textarea.js';
let rootFontSize = parseFloat(document.documentElement.style.fontSize);
import appCommonH from '../../../../../helpers/commonHelper';
import browserDb from '../../../../../helpers/webDbHelper';


export default {
  data:function () {
    return {
      headerTitle:"回复主题",
      // content:'',
      showFacePanel: false,
      keyboard: false,
      replyText:'',
      replyQuote:'',
      replyQuoteCont:'',
      keywordsMax: 1000,
      footMove: false,
      faceData:[],
      fileList: [
        // Uploader 根据文件后缀来判断是否为图片文件
        // 如果图片 URL 中不包含类型信息，可以添加 isImage 标记来声明
        // { url: 'https://cloud-image', isImage: true }
      ],
      uploadShow:false,
      fileListOne:[],
      enclosureList:[],
      isWeixin: false,
      isPhone: false,
      supportImgExt: '',
      supportImgExtRes:'',
      limitMaxLength:true,
      fileListOne:[],
      fileListOneLen:'',
      canUploadImages:'',
      backGo:-3,
      viewportWidth: '',
      queryEdit: '',
      // userId: '',
      canEdit: '',
    }
  },
  computed: {
    themeId: function () {
      return this.$route.params.themeId;
    },
    replyId: function () {
      return this.$route.params.replyId;
    }, 
  },
  created(){
    this.queryEdit = this.$route.query.edit;
    // this.userId = browserDb.getLItem('tokenId');
    // if(!this.userId){
    //   this.$toast.fail('未登录状态下不能进入编辑回复页');
    // }
    if(this.queryEdit == 'reply'){
        this.replyDetailsLoad();
        this.headerTitle = '编辑回复';
    }
    this.isWeixin = appCommonH.isWeixin().isWeixin;
    this.isPhone = appCommonH.isWeixin().isPhone;
    this.viewportWidth = window.innerWidth;
    var u = navigator.userAgent;
    this.isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1; //android终端
    this.isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
    this.replyQuoteCont = browserDb.getLItem('replyQuote');
    this.replyQuote = '<blockquote class="quoteCon">'+ this.replyQuoteCont +'</blockquote>';
    this.getInfo(); //初始化请求接口，判断是否有权限
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
  watch: {
    'fileListOne.length': function(newVal,oldVal){
      this.fileListOneLen = newVal;
      if(this.fileListOneLen >= 12){
        this.limitMaxLength = false;
      } else {
        this.limitMaxLength = true;
      }
    },
    showFacePanel: function(newVal,oldVal){
      this.showFacePanel = newVal;
      if(this.showFacePanel) {
        document.getElementById('postForm').style.height = (this.viewportHeight - 240) + 'px';
      } else {
        document.getElementById('postForm').style.height = '100%';
      }
    },
  },
  beforeDestroy () {
      Bus.$off('message');
  },
  methods: {
    getInfo(){
      //请求站点信息，用于判断是否能上传附件
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
          this.canUploadImages = res.readdata._data.other.can_upload_images;
        }
      });
    },


    //设置底部在pc里的宽度
    limitWidth(){
      document.getElementById('post-topic-footer').style.width = "640px";
      let viewportWidth = window.innerWidth;
      document.getElementById('post-topic-footer').style.left = (viewportWidth - 640)/2+'px';
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

    //上传图片,点击加号时
    handleFile(e){
     let files = [];
     if(e.length === undefined) {
       files.push(e);
     } else {
       files = e;
     }
     if(!this.limitMaxLength){
       this.$toast.fail('已达上传图片上限');
     } else {
       files.map((file,index) => {
         if(this.isAndroid && this.isWeixin){
           this.testingType(file.file,this.supportImgExt);
           if(this.testingRes){
             this.compressFile(file.file, 150000, false,files.length - index);
           }
         } else {
           this.compressFile(file.file, 150000, false, files.length - index);
         }
       });
     }
    },

    //上传图片，点击底部Icon时
    handleFileUp(e){
      let fileListNowLen =  e.target.files.length + this.fileListOne.length <= 12?e.target.files.length : 12 - this.fileListOne.length;
      for(var i = 0; i < fileListNowLen; i++){
        var file = e.target.files[i];
        if(this.isAndroid && this.isWeixin){
          this.testingType(file,this.supportImgExt);
          if(this.testingRes){
            this.compressFile(file, 150000, true);
          }
        } else {
          this.compressFile(file, 150000, true);
        }
      }
    },

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

    // 删除图片
    deleteEnclosure(id,type){
      if(this.fileListOne.length<1){
        this.uploadShow = false;
      }
      this.appFetch({
        url:'attachment',
        method:'delete',
        splice:'/'+id.id,
      })
    },

    //这里写接口，上传
    // uploaderEnclosure(file,isFoot,img){
      uploaderEnclosure(file,isFoot,img,enclosure,index){
        this.appFetch({
          url:'attachment',
          method:'post',
          data:file,

        }).then(data=>{
          if (data.errors){
            this.$toast.fail(data.errors[0].code);
            throw new Error(data.error)
          } else {
            if (img) {
              this.fileList.push({url:data.readdata._data.url,id:data.readdata._data.id});
              this.fileListOne[this.fileListOne.length - index].id = data.data.attributes.id;
            }
            if (isFoot) {
              this.fileListOne.push({url:data.readdata._data.url,id:data.readdata._data.id});
              // 当上传一个文件成功 时，显示组件，否则不处理
              if (this.fileListOne.length>0){
                this.uploadShow = true;
              }
            }
          }
        })
    },
    //压缩
    // compressFile(file, uploadShow, wantedSize = 150000, event){
    compressFile(file, wantedSize, uploadShow, index){
      const curSize = file.size || file.length * 0.8
      const quality = Math.max(wantedSize / curSize, 0.8)
      let that = this;
      lrz(file, {
          quality: 0.8, //设置压缩率
      }).then(function (rst) {
          let formdata = new FormData();
          formdata.append('file', rst.file, file.name);
          formdata.append('isGallery', 1);
          // that.uploaderEnclosure(formdata, uploadShow, !uploadShow);
          that.uploaderEnclosure(formdata, uploadShow, !uploadShow, false,index);
          that.loading = false;
      }).catch(function (err) {
          /* 处理失败后执行 */
      }).always(function () {
          /* 必然执行 */
      })
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
      const value = this.replyText;
      const el = this.$refs.textarea;
      const startPos = el.selectionStart;
      const endPos = el.selectionEnd;
      const newValue = value.substring(0, startPos) + face + value.substring(endPos, value.length)
      this.replyText = newValue
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
    addExpression(){
      this.keyboard = !this.keyboard;
      this.appFetch({
        url: 'emojis',
        method: 'get',
        data: {
          include: '',
        }
      }).then((data) => {
        if (data.errors){
          this.$toast.fail(data.errors[0].code);
          throw new Error(data.error)
        } else {
          this.faceData = data.readdata;
        }
      })
      this.showFacePanel = !this.showFacePanel;
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
      var id = value.id;
      this.cateId = id;
      var text = value.text;
      this.showPopup = false;
      this.selectSort = value.text;
    },

    //初始化请求回复数据
    replyDetailsLoad(){
      this.appFetch({
        url: 'posts',
        method: 'get',
        splice:'/'+this.replyId,
        data: {
          include: ['user', 'images'],
        }
      }).then((res) => {
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        } else {
          // console.log(res,'~~~~~');
          this.canEdit = res.readdata._data.canEdit;
          if(!this.canEdit){
            this.$toast.fail('您没有权限进行此操作');
            this.$router.replace({path:'/'}); 
          }
          var fileListCon = res.readdata.images;
          this.replyText = res.readdata._data.content;
          for (var i = 0; i < fileListCon.length; i++) {
            this.fileListOne.push({url:fileListCon[i]._data.thumbUrl,id:fileListCon[i]._data.id});
          }

          if(this.fileListOne.length>0){
            this.uploadShow = true;
          }
        }
      })
    },
    


    //回复主题
    publish(){
      if(this.replyText == '' || this.replyText == null){
        this.$toast.fail('内容不能为空');
        return;
      }
      this.attriAttachment = this.fileListOne;
      for(let m=0;m<this.attriAttachment.length;m++){
        this.attriAttachment[m] = {
          "type": "attachments",
          "id": this.attriAttachment[m].id
        }
      }
      var posts = '';
      var methodType = '';
      var  dataCon = '';
      if(this.queryEdit == 'reply'){
        //当编辑回复时
        posts = 'posts/' + this.replyId;
        methodType = 'patch';
        dataCon = {
          "data": {
            "attributes": {
              "content": this.replyText
            },
            "relationships": {
              "attachments": {
                "data":this.attriAttachment
              },
            },
          }
        }
      } else {
        //当正常回复时
        posts = 'posts';
        methodType = 'post';
        if(this.replyId && this.replyQuoteCont && this.replyText != ''){
          //当是回复的回复时
          dataCon = {
            "data": {
              "type": "posts",
              "attributes": {
                  "replyId": this.replyId,
                  "content": this.replyQuote + this.replyText
              },
              "relationships": {
                  "thread": {
                      "data": {
                          "type": "threads",
                          "id": this.themeId
                      }
                  },
                  "attachments": {
                    "data":this.attriAttachment
                  },
              },
            }
          };
        } else {
          //当是主题回复时
          dataCon = {
            "data": {
              "type": "posts",
              "attributes": {
                  "content": this.replyText
              },
              "relationships": {
                  "thread": {
                      "data": {
                          "type": "threads",
                          "id": this.themeId
                      }
                  },
                  "attachments": {
                    "data":this.attriAttachment
                  },
              },
            }
          }
        }
      }
      this.appFetch({
        url: posts,
        method: methodType,
        data: dataCon,
        
      }).then(res =>{
        if (res.errors){
          if (res.errors[0].detail){
            this.$toast.fail(res.errors[0].code + '\n' + res.errors[0].detail[0])
          } else {
            this.$toast.fail(res.errors[0].code);
          }
        } else {
          this.$router.replace({path:'/details'+'/'+this.themeId,query:{backGo:this.backGo},replace: true})
        }
      })

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
    // searchChange: debounce(function () {
    //   let trim = this.keywords.trim();
    //   if (!trim) {
    //     this.list = [];
    //     return;
    //   }
    //   const params = {
    //     keywords: this.keywords
    //   }
    //   // 调api ...
    // }),
    // handleFaceChoose (face) {
    //   const value = this.replyText
    //   const el = this.$refs.textarea
    //   const startPos = el.selectionStart
    //   const endPos = el.selectionEnd
    //   const newValue =
    //     value.substring(0, startPos) +
    //     face +
    //     value.substring(endPos, value.length)
    //   this.replyText = newValue
    //   if (el.setSelectionRange) {
    //     setTimeout(() => {
    //       const index = startPos + face.length
    //       el.setSelectionRange(index, index)
    //     }, 0)
    //   }
    // },

    backClick() {
      this.$router.go(-1);
    },
  },

  destroyed: function () {
      browserDb.removeLItem('replyQuote');
  },
  /*beforeRouteEnter (to,from,next){
    next(vm=>{
      if (from.name === 'details/:themeId'){
        vm.backGo = '/';
      }
    });
  }*/
}
