/**
 * 发布主题控制器
 */
import { debounce, autoTextarea } from '../../../../../common/textarea.js';
import Store from '../../../../../common/Store.js';
import Post from '../../../../../common/models/Post.js';
import appCommonH from '../../../../../helpers/commonHelper';
let rootFontSize = parseFloat(document.documentElement.style.fontSize);
export default {
  data:function () {
    return {
      headerTitle:"发布主题",
      selectSort:'选择分类',
      showPopup:false,
      categories: [],
      categoriesId: [],
      cateId:'',
      content:'',
      showFacePanel: false,
      keyboard: false,
      // expressionShow: false,
      keywordsMax: 1000,
      list: [],
      footMove: false,
      faceData:[],
      fileList: [
        // Uploader 根据文件后缀来判断是否为图片文件
        // 如果图片 URL 中不包含类型信息，可以添加 isImage 标记来声明
        // { url: 'https://ss1.bdstatic.com/70cFuXSh_Q1YnxGkpoWK1HF6hhy/it/u=88704046,262850083&fm=11&gp=0.jpg', isImage: true }
      ],
      fileListOne:[],
      uploadShow:false,
      enclosureList:[
        // {
        //   type:'doc',
        //   name:'saaaaaaaa',
        // },
      ],
      avatar: "",
      themeId:'',
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
      canUploadImages:'',
      canUploadAttachments:'',
      supportImgExt: '',
      supportFileExt:'',
      supportFileArr:'',
      limitMaxLength:true,
      limitMaxEncLength:true,
      fileListOneLen:'',
      enclosureListLen:'',
      isiOS: false,
      encuploadShow: false

    }
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
      if(this.isWeixin != true && this.isPhone != true){
        this.limitWidth();
      }
  },
  created(){
    this.isWeixin = appCommonH.isWeixin().isWeixin;
    this.isPhone = appCommonH.isWeixin().isPhone;
    var u = navigator.userAgent;
    this.isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1; //android终端
    this.isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
    if(this.isiOS) {
      this.encuploadShow = true;
      console.log(this.encuploadShow);
    }
    if(this.$route.params.themeId){
      var themeId = this.$route.params.themeId;
      var postsId = this.$route.params.postsId;
      var themeContent = this.$route.params.themeContent;
      // console.log(themeId)
      this.themeId = themeId;
      this.postsId = postsId;
      this.content = themeContent;
    }
    //初始化请求分类接口
    this.loadCategories();
    //初始化请求主题数据
    this.detailsLoad();
    this.getInfo();


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
    },
    'enclosureList.length': function(newVal,oldVal){
      this.enclosureListLen = newVal;
      if(this.enclosureListLen >= 3){
        this.limitMaxEncLength = false;
      } else {
        this.limitMaxEncLength = true;
      }
      console.log(this.enclosureListLen+'sssss');
    },
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
          for(var k=0;k<ImgExt.length;k++){
            ImgStr = '.'+ImgExt[k]+',';
            this.supportImgExt += ImgStr;
          }
          var fileExt = res.readdata._data.supportFileExt.split(',');
          var fileStr='';
          for(var k=0;k<fileExt.length;k++){
            fileStr = '.'+fileExt[k]+',';
            this.supportFileExt += fileStr;
          }
          this.canUploadImages = res.readdata._data.canUploadImages;
          this.canUploadAttachments = res.readdata._data.canUploadAttachments;
        }
      });
    },
    //初始化请求编辑主题数据
    detailsLoad(){
      if(this.postsId && this.content){
        let threads = 'threads/'+this.themeId;
        this.appFetch({
          url: threads,
          method: 'get',
          data: {
            include: ['firstPost',  'firstPost.images', 'firstPost.attachments', 'category'],
          }
        }).then((res) => {
          if (res.errors){
            this.$toast.fail(res.errors[0].code);
            throw new Error(res.error)
          } else {
            console.log(res);
            console.log('1234');
            // this.enclosureList = res.readdata.attachments;
            // this.fileList = res.readdata.images;
            console.log(this.cateId);
            const initializeCateId = res.readdata.category._data.id;
            this.selectSort = res.readdata.category._data.description;
            console.log(this.selectSort);
            if(this.cateId != initializeCateId){
              this.cateId = initializeCateId;
            }
          }

        })
      }
    },
    //发布主题
    publish(){
      if(this.postsId && this.content){
        console.log('回复');
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
            this.$toast.fail(res.errors[0].code);
            throw new Error(res.error)
          } else {
            this.$router.push({ path:'details'+'/'+this.themeId});
          }
        })
      } else {
        this.attriAttachment = this.fileListOne.concat(this.enclosureList);
        for(let m=0;m<this.attriAttachment.length;m++){
          this.attriAttachment[m] = {
            "type": "attachments",
            "id": this.attriAttachment[m].id
          }
        }

        this.appFetch({
          url:"threads",
          method:"post",
          data:{
            "data": {
              "type": "threads",
              "attributes": {
                  "content": this.content,
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
            this.$toast.fail(res.errors[0].code);
            throw new Error(res.error)
          }else{
            var postThemeId = res.readdata._data.id;
            var _this = this;
            _this.$router.push({ path:'details'+'/'+postThemeId});
          }
        })
      }
    },

    //设置底部在pc里的宽度
    limitWidth(){
      document.getElementById('post-topic-footer').style.width = "640px";
      let viewportWidth = window.innerWidth;
      document.getElementById('post-topic-footer').style.marginLeft = (viewportWidth - 640)/2+'px';
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

    // 删除附件
    deleteEnc(id,type){
      if(this.fileListOne.length<1){
        this.uploadShow = false;
      }
      this.appFetch({
        url:'attachment',
        method:'delete',
        splice:'/'+id.id
      }).then(data=>{
        var newArr = this.enclosureList.filter(item => item.id !== id.id);
        this.enclosureList = newArr;
        console.log(this.enclosureList);
        console.log('2567');

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

    //上传图片,点击加号时
    handleFile(e){
      this.compressFile(e.file, false);
    },
    //上传图片，点击底部Icon时
    handleFileUp(e){
        this.compressFile(e.target.files[0], true);
    },

    //上传附件
    handleEnclosure(e){
      let file = e.target.files[0];
      let formdata = new FormData();
      formdata.append('file', file);
      formdata.append('isGallery', 0);
      this.loading = true,
      this.uploaderEnclosure(formdata,false,false,true);

    },

    // 这里写接口，上传
    uploaderEnclosure(file,isFoot,img,enclosure){
      console.log(file,isFoot,enclosure);
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
            this.fileListOne[this.fileListOne.length-1].id = data.data.attributes.id;
          }
          if (isFoot) {
            this.fileListOne.push({url:data.readdata._data.url,id:data.readdata._data.id});
            // 当上传一个文件成功 时，显示组件，否则不处理
            if (this.fileListOne.length>0){
              this.uploadShow = true;
            }

          }
          if (enclosure) {
            this.enclosureShow = true
            this.enclosureList.push({
               type:data.readdata._data.extension,
               name:data.readdata._data.fileName,
               id:data.readdata._data.id
            });

          }
          this.loading = false;
        }
      })
    },
    
    //压缩图片
    compressFile(file, uploadShow, wantedSize = 150000, event){
      const curSize = file.size || file.length * 0.8
      const quality = Math.max(wantedSize / curSize, 0.8)
      let that = this;
      lrz(file, {
          quality: 0.8, //设置压缩率
      }).then(function (rst) {
          let formdata = new FormData();
          formdata.append('file', rst.file, file.name);
          formdata.append('isGallery', 1);
          that.uploaderEnclosure(formdata, uploadShow, !uploadShow);
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
          console.log(res, 'res1111');
          var newCategories = [];
          newCategories = res.readdata;
          console.log(res.readdata);
          for(let j = 0,len=newCategories.length; j < len; j++) {
            // console.log(newCategories[j]._data);
            this.categories.push(
              {
                'text': newCategories[j]._data.name,
                'id':newCategories[j]._data.id
              }
            );
            // console.log(this.categories)
            this.categoriesId.push(newCategories[j]._data.id);
          }
        }
      })
    },
    onCancel() {
      this.showPopup = false;
    }

  }
}
