import {Bus} from '../../../store/bus.js';
import { debounce, autoTextarea } from '../../../../../common/textarea.js';
let rootFontSize = parseFloat(document.documentElement.style.fontSize);
import appCommonH from '../../../../../helpers/commonHelper';
export default {
  data:function () {
    return {
      headerTitle:"回复主题",
      // content:'',
      showFacePanel: false,
      keyboard: false,
      replyText:'',
      keywordsMax: 1000,
      footMove: false,
      faceData:[],
      fileList: [
        // Uploader 根据文件后缀来判断是否为图片文件
        // 如果图片 URL 中不包含类型信息，可以添加 isImage 标记来声明
        // { url: 'https://cloud-image', isImage: true }
      ],
      uploadShow:false,
      replyId:'',
      themeId:'',
      isWeixin: false,
      isPhone: false
    }
  },
  created(){
    this.isWeixin = appCommonH.isWeixin().isWeixin;
    this.isPhone = appCommonH.isWeixin().isPhone;
    var replyQuote = this.$route.params.replyQuote;
    var replyId = this.$route.params.replyId;
    var themeId = this.$route.params.themeId;
    // console.log(replyQuote);
    // console.log(replyId);
    // console.log(themeId+'2222');
    if(replyId && replyQuote){
      this.replyText = '<blockquote class="quoteCon">'+replyQuote+'</blockquote>';
    } else {
      this.replyText = '';
    }
    this.replyId = replyId;
    this.themeId = themeId;
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
  beforeDestroy () {
      Bus.$off('message');
  },
  methods: {
    //设置底部在pc里的宽度
    limitWidth(){
      document.getElementById('post-topic-footer').style.width = "640px";
      let viewportWidth = window.innerWidth;
      document.getElementById('post-topic-footer').style.marginLeft = (viewportWidth - 640)/2+'px';
    },
    //上传图片,点击加号时
    handleFile(e){
      // 实例化
      // console.log(e);
      let formdata = new FormData()
      formdata.append('file', e.file);
      formdata.append('isGallery', 1);
      this.uploaderEnclosure(formdata);

    },
    //上传图片，点击底部Icon时
    handleFileUp(e){
      let file = e.target.files[0];
      let formdata = new FormData();
      formdata.append('file', file);
      formdata.append('isGallery', 1);
      this.uploaderEnclosure(formdata,true);
      this.uploadShow = true;
    },
    // //删除图片
    // deleteFile(){
    //   // alert('刪除');
    //   if(this.fileList.length<=1){
    //     this.uploadShow = false;
    //   }
    //   //调接口
    // },

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
    uploaderEnclosure(file,isFoot){
        this.appFetch({
          url:'attachment',
          method:'post',
          data:file,

        }).then(data=>{
          if (data.errors){
            this.$toast.fail(data.errors[0].code);
            throw new Error(data.error)
          }else{
            if(isFoot){
             this.fileList.push({url:data.readdata._data.url,id:data.readdata._data.id});
            }
           }
          // this.$message('提交成功');
          this.$toast.success('提交成功');
        }).catch(error=>{
          this.$toast.fail('失败');
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
      this.attriAttachment = this.fileList;
      for(let m=0;m<this.attriAttachment.length;m++){
        this.attriAttachment[m] = {
          "type": "attachments",
          "id": this.attriAttachment[m].id
        }
      }
      if(this.replyId && this.replyText){
        this.appFetch({
          url:"posts",
          method:"post",
          data:{
            "data": {
                "type": "posts",
                "attributes": {
                    "replyId": this.replyId,
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
            this.$toast.fail(res.errors[0].code);
            throw new Error(res.error)
          } else {
            this.$router.push({path:'details'+'/'+this.themeId})
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
            this.$toast.fail(res.errors[0].code);
            throw new Error(res.error)
          } else {
            this.$router.push({path:'details'+'/'+this.themeId});
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
  }
}
