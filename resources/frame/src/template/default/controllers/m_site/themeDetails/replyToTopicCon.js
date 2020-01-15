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
      isWeixin: false,
      isPhone: false,
      supportImgExt: '',
      supportImgExtRes:'',
      limitMaxLength:true,
      fileListOne:[],
      canUploadImages:'',
      backGo:-3
    }
  },
  computed: {
    themeId: function () {
      return this.$route.params.themeId;
    },
    replyId: function () {
      return this.$route.params.replyId;
    }
  },
  created(){
    console.log(this.$route);
    console.log('4444');
    this.isWeixin = appCommonH.isWeixin().isWeixin;
    this.isPhone = appCommonH.isWeixin().isPhone;
    var u = navigator.userAgent;
    this.isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1; //android终端
    this.isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
    // var replyQuote = this.$route.params.replyQuote;
    this.replyQuoteCont = browserDb.getLItem('replyQuote');
    this.replyQuote = '<blockquote class="quoteCon">'+ this.replyQuoteCont +'</blockquote>';
    // var replyId = this.$route.params.replyId;
    // var themeId = this.$route.params.themeId;
    // console.log(replyQuote);
    console.log(this.replyId+'1111');
    console.log(this.themeId+'2222');
    /*if(this.replyId && replyQuote){
      this.replyText = '';
    } else {
      this.replyText = '';
    }*/
    // this.replyId = replyId;
    // this.themeId = themeId;
    this.getInfo(); //初始化请求接口，判断是否有权限
  },

  mounted () {
    this.$nextTick(() => {
      let textarea = this.$refs.textarea;
      textarea.focus();
      let prevHeight = 300;
      textarea && autoTextarea(textarea, 5, 0, (height) => {
        height += 20;
        if (height !== prevHeight) {
          prevHeight = height;
          let rem = height / rootFontSize;
          // this.$refs.list.style.height = `calc(100% - ${rem}rem)`;
        }
      });
    })
    //设置在pc的宽度
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
      console.log(this.fileListOneLen+'dddd');
    }
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
          console.log(res);
          console.log('888887');
           var ImgExt = res.readdata._data.supportImgExt.split(',');
           var ImgStr='';
           var imgStrRes ='';
          for(var k=0;k<ImgExt.length;k++){
            ImgStr = '.'+ImgExt[k]+',';
            imgStrRes = 'image/'+ImgExt[k]+',';
            this.supportImgExt += ImgStr;
            this.supportImgExtRes += imgStrRes;
          }
          this.canUploadImages = res.readdata._data.canUploadImages;
          console.log(this.canUploadImages+'5555');
        }
      });
    },


    //设置底部在pc里的宽度
    limitWidth(){
      document.getElementById('post-topic-footer').style.width = "640px";
      let viewportWidth = window.innerWidth;
      document.getElementById('post-topic-footer').style.marginLeft = (viewportWidth - 640)/2+'px';
    },

    //上传之前先判断是否有权限上传图片
    beforeHandleFile(){
      console.log(this.canUploadImages+'0099')
      if(!this.canUploadImages){
        this.$toast.fail('没有上传图片的权限');
      } else {
        if(!this.limitMaxLength){
          this.$toast.fail('已达上传图片上限');
        }
      }
    },

    //上传图片,点击加号时
    // handleFile(e){
    //   if(this.isAndroid && this.isWeixin){
    //     this.testingType(e.file,this.supportImgExt);
    //     console.log(this.testingRes+'445');
    //     if(this.testingRes){
    //       this.compressFile(e.file, false);
    //     }
    //   } else {
    //     this.compressFile(e.file, false);
    //   }
    // },

    // //上传图片，点击底部Icon时
    // handleFileUp(e){
    //   if(this.isAndroid && this.isWeixin){
    //     this.testingType(e.target.files[0],this.supportImgExt);
    //     if(this.testingRes){
    //       this.compressFile(e.target.files[0], true);
    //     }
    //   } else {
    //     this.compressFile(e.target.files[0], true);
    //   }
    // },

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
           // console.log(this.testingRes+'445');
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




    deleteEnclosure(id,type){
      console.log(id);

      // return false;
      if(this.fileList.length<=1){
        this.uploadShow = false;
      }
      this.appFetch({
        url:'attachment',
        method:'delete',
        splice:'/'+id
      }).then(data=>{
        if (data.errors){
          this.$toast.fail(data.errors[0].code);
          throw new Error(data.error)
        } else {
          var attriAttachment = new Array();
          if(type == "img"){
            var newArr = this.fileList.filter(item => item.id !== id);
            this.fileList = newArr;
            console.log(this.fileList);
          }
          this.$toast.success('删除成功');
        }
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
              console.log(this.fileList);
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


    //压缩图片
    // compressFile(file, uploadShow, wantedSize = 150000, event){
    //   const curSize = file.size || file.length * 0.8
    //   const quality = Math.max(wantedSize / curSize, 0.8)
    //   // let that = this;
    //   lrz(file, {
    //       quality: 0.8, //设置压缩率
    //   }).then(function (rst) {
    //       alert('压缩');
    //       let formdata = new FormData();
    //       formdata.append('file', rst.file, file.name);
    //       fromdata.append('isGallery', 1);
    //       this.uploaderEnclosure(formdata, uploadShow, !uploadShow);
    //       // that.loading = false;


    //   }).catch(function (err) {
    //       /* 处理失败后执行 */
    //   }).always(function () {
    //       /* 必然执行 */
    //   })
    // },
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
      console.log(face);
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
      this.footMove = !this.footMove;
    },
    backClick() {
      this.$router.go(-1);
    },
    dClick() {
      this.showPopup = true;
    },
    onConfirm( value, index) {
      console.log(value);
      var id = value.id;
      this.cateId = id;
      console.log(this.cateId);
      var text = value.text;
      this.showPopup = false;
      this.selectSort = value.text;
    },


    //回复主题
    publish(){
      // this.attriAttachment = this.fileListOne;
      // for(let m=0;m<this.attriAttachment.length;m++){
      //   this.attriAttachment[m] = {
      //     "type": "attachments",
      //     "id": this.attriAttachment[m].id
      //   }
      // }

      this.attriAttachment = this.fileListOne;
      for(let m=0;m<this.attriAttachment.length;m++){
        this.attriAttachment[m] = {
          "type": "attachments",
          "id": this.attriAttachment[m].id
        }
      }
      if(this.replyId && this.replyQuoteCont){
        this.appFetch({
          url:"posts",
          method:"post",
          data:{
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
          },
        }).then(res =>{
          if (res.errors){
            this.$toast.fail(res.errors[0].code);
            throw new Error(res.error)
          } else {
            this.$router.push({path:'/details'+'/'+this.themeId,query:{backGo:this.backGo}})
          }
        })
      } else {
        // alert('2222');
        this.appFetch({
          url:"posts",
          method:"post",
          data:{
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
          },
        }).then(res =>{
          if (res.errors){
            this.$toast.fail(res.errors[0].code + '\n' + res.errors[0].detail[0]);
            throw new Error(res.error)
          } else {
            this.$router.push({path:'/details'+'/'+this.themeId,query:{backGo:this.backGo}});
          }
        })
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
    console.log(to.name);
    console.log(from.name);
    next(vm=>{
      if (from.name === 'details/:themeId'){
        console.log('回退2');
        vm.backGo = '/';
      }
    });
  }*/
}
