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
      attriAttachment:false
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
  },
  watch: {

  },
  methods: {
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
            let a = this.apiStore.pushPayload(res);
            this.$router.push({ path:'details'+'/'+this.themeId});
          }
        })
      } else {
        this.attriAttachment = this.fileList.concat(this.enclosureList);
         // var aa = new Array();
        for(let m=0;m<this.attriAttachment.length;m++){
          this.attriAttachment[m] = {
            "type": "attachments",
            "id": this.attriAttachment[m].id
          }
        }
        console.log(this.attriAttachment);
        // return false;
        // console.log('发帖');
        // return false;
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
            this.$router.push({ path:'details'+'/'+postThemeId});
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
    //上传图片,点击加号时
    handleFile(e){
      // 实例化
      // console.log(e);
      let formdata = new FormData()
      formdata.append('file', e.file);
      formdata.append('isGallery', 1);
      this.uploaderEnclosure(formdata,false);
      this.loading = false;

    },
    //上传图片，点击底部Icon时
    handleFileUp(e){
      let file = e.target.files[0];
      let formdata = new FormData();
      formdata.append('file', file);
      formdata.append('isGallery', 1);
      this.uploaderEnclosure(formdata,true);
      this.uploadShow = true;
      this.loading = false;
    },
    //删除图片
    // deleteFile(uuid){
    //   // alert('刪除');
    //   if(this.fileList.length<=1){
    //     this.uploadShow = false;
    //   }
    //    //调接口
    // },
    //删除附件
    deleteEnclosure(id,type){
      console.log(id,type);

      // return false;
      if(this.fileList.length<1){
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
            console.log('104');
            // for(var h=0;k<this.fileList.length;h++){
            //   var data = {};
            //   data.type = 'attachments';
            //   data.id = this.fileList[h].id;
            //   attriAttachment.push(data);
            // }
            // this.attriAttachment = attriAttachment;
          } else {
            var newArr = this.enclosureList.filter(item => item.id !== id);
            this.enclosureList = newArr;
            console.log(this.enclosureList);
            console.log('2567');
            // for(var k=0;k<this.enclosureList.length;k++){
            //   var data = {};
            //   data.type = 'attachments';
            //   data.id = this.enclosureList[k].id;
            //   attriAttachment.push(data);
            // }
            // this.attriAttachment = attriAttachment;
            // console.log(this.attriAttachment);
          }
          this.$toast.success('删除成功');
        }
      })
    },


    //上传附件
    handleEnclosure(e){
      let file = e.target.files[0];
      let formdata = new FormData();
      formdata.append('file', file);
      formdata.append('isGallery', 0);
      this.loading = true,
      this.uploaderEnclosure(formdata,false,true);
    },
    // 组件方法 获取 流
    // async onRead(file) {
    //   // console.log(file.file);
    //   this.files.name = file.file.name; // 获取文件名
    //   this.files.type = file.file.type; // 获取类型
    //   this.picValue = file.file; // 文件流
    //   this.imgPreview(this.picValue);
    // },
    // 处理图片
    // imgPreview(file) {
    //   let self = this;
    //   let Orientation;
    //   //去获取拍照时的信息，解决拍出来的照片旋转问题
    //   // Exif.getData(file, function () {
    //   //   Orientation = Exif.getTag(this, "Orientation");
    //   // });
    //   // 看支持不支持FileReader
    //   if (!file || !window.FileReader) return;
    //   if (/^image/.test(file.type)) {
    //     // 创建一个reader
    //     let reader = new FileReader();
    //     // 将图片2将转成 base64 格式
    //     reader.readAsDataURL(file);
    //     // 读取成功后的回调
    //     reader.onloadend = function () {
    //       // console.log(this.result);
    //       let result = this.result;
    //       let img = new Image();
    //       img.src = result;
    //       //判断图片是否大于500K,是就直接上传，反之压缩图片
    //       if (this.result.length <= 500 * 1024) {
    //         self.headerImage = this.result;
    //         self.uploaderEnclosure();
    //       } else {
    //         img.onload = function () {
    //           let data = self.compress(img, Orientation);
    //           self.headerImage = data;
    //           self.uploaderEnclosure();
    //         };
    //       }
    //     };
    //   }
    // },
    // 压缩图片
    // compress(img, Orientation) {
    //   let canvas = document.createElement("canvas");
    //   let ctx = canvas.getContext("2d");
    //   //瓦片canvas
    //   let tCanvas = document.createElement("canvas");
    //   let tctx = tCanvas.getContext("2d");
    //   // let initSize = img.src.length;
    //   let width = img.width;
    //   let height = img.height;
    //   //如果图片大于四百万像素，计算压缩比并将大小压至400万以下
    //   let ratio;
    //   if ((ratio = (width * height) / 4000000) > 1) {
    //     // console.log("大于400万像素");
    //     ratio = Math.sqrt(ratio);
    //     width /= ratio;
    //     height /= ratio;
    //   } else {
    //     ratio = 1;
    //   }
    //   canvas.width = width;
    //   canvas.height = height;
    //   //    铺底色
    //   ctx.fillStyle = "#fff";
    //   ctx.fillRect(0, 0, canvas.width, canvas.height);
    //   //如果图片像素大于100万则使用瓦片绘制
    //   let count;
    //   if ((count = (width * height) / 1000000) > 1) {
    //     // console.log("超过100W像素");
    //     count = ~~(Math.sqrt(count) + 1); //计算要分成多少块瓦片
    //     //      计算每块瓦片的宽和高
    //     let nw = ~~(width / count);
    //     let nh = ~~(height / count);
    //     tCanvas.width = nw;
    //     tCanvas.height = nh;
    //     for (let i = 0; i < count; i++) {
    //       for (let j = 0; j < count; j++) {
    //         tctx.drawImage(img, i * nw * ratio, j * nh * ratio, nw * ratio, nh * ratio, 0, 0, nw, nh);
    //         ctx.drawImage(tCanvas, i * nw, j * nh, nw, nh);
    //       }
    //     }
    //   } else {
    //     ctx.drawImage(img, 0, 0, width, height);
    //   }
    //   //修复ios上传图片的时候 被旋转的问题
    //   if (Orientation != "" && Orientation != 1) {
    //     switch (Orientation) {
    //       case 6: //需要顺时针（向左）90度旋转
    //         this.rotateImg(img, "left", canvas);
    //         break;
    //       case 8: //需要逆时针（向右）90度旋转
    //         this.rotateImg(img, "right", canvas);
    //         break;
    //       case 3: //需要180度旋转
    //         this.rotateImg(img, "right", canvas); //转两次
    //         this.rotateImg(img, "right", canvas);
    //         break;
    //     }
    //   }
    //   //进行最小压缩
    //   let ndata = canvas.toDataURL("image/jpeg", 0.1);
    //   tCanvas.width = tCanvas.height = canvas.width = canvas.height = 0;
    //   return ndata;
    // },
    // 旋转图片
    // rotateImg(img, direction, canvas) {
    //   //最小与最大旋转方向，图片旋转4次后回到原方向
    //   const min_step = 0;
    //   const max_step = 3;
    //   if (img == null) return;
    //   //img的高度和宽度不能在img元素隐藏后获取，否则会出错
    //   let height = img.height;
    //   let width = img.width;
    //   let step = 2;
    //   if (step == null) {
    //     step = min_step;
    //   }
    //   if (direction == "right") {
    //     step++;
    //     //旋转到原位置，即超过最大值
    //     step > max_step && (step = min_step);
    //   } else {
    //     step--;
    //     step < min_step && (step = max_step);
    //   }
    //   //旋转角度以弧度值为参数
    //   let degree = (step * 90 * Math.PI) / 180;
    //   let ctx = canvas.getContext("2d");
    //   switch (step) {
    //     case 0:
    //       canvas.width = width;
    //       canvas.height = height;
    //       ctx.drawImage(img, 0, 0);
    //       break;
    //     case 1:
    //       canvas.width = height;
    //       canvas.height = width;
    //       ctx.rotate(degree);
    //       ctx.drawImage(img, 0, -height);
    //       break;
    //     case 2:
    //       canvas.width = width;
    //       canvas.height = height;
    //       ctx.rotate(degree);
    //       ctx.drawImage(img, -width, -height);
    //       break;
    //     case 3:
    //       canvas.width = height;
    //       canvas.height = width;
    //       ctx.rotate(degree);
    //       ctx.drawImage(img, -width, 0);
    //       break;
    //   }
    // },
    //将base64转换为文件
    // dataURLtoFile(dataurl) {
    //   var arr = dataurl.split(","),
    //     bstr = atob(arr[1]),
    //     n = bstr.length,
    //     u8arr = new Uint8Array(n);
    //   while (n--) {
    //     u8arr[n] = bstr.charCodeAt(n);
    //   }
    //   return new File([u8arr], this.files.name, {
    //     type: this.files.type
    //   });
    // },
    //这里写接口，上传
    uploaderEnclosure(file,isFoot,enclosure){
      console.log(file,isFoot,enclosure)
       this.appFetch({
         url:'attachment',
         method:'post',
         data:file,

       }).then(data=>{
        if (data.errors){
          this.$toast.fail(data.errors[0].code);
          throw new Error(data.error)
        } else {
          console.log(data);
          // console.log('909090');
          // var attriAttachment = new Array();

          if(isFoot){
            console.log('图片');
            this.fileList.push({url:data.readdata._data.url,id:data.readdata._data.id});
           // console.log(this.fileList);
           // for(var h=0;h<this.fileList.length;h++){
           //   var data = {};
           //   data.type = 'attachments';
           //   data.id = this.fileList[h].id;
           //   console.log('1111');
           //   attriAttachment.push(data);
           // }
          }
           if(enclosure){
             console.log('fujian');
             this.enclosureShow = true
             this.enclosureList.push({type:data.readdata._data.extension,name:data.readdata._data.fileName,id:data.readdata._data.id});

             console.log(this.enclosureList);
             // for(var k=0;k<this.enclosureList.length;k++){
             //   var data = {};
             //   data.type = 'attachments';
             //   data.id = this.enclosureList[k].id;
             //   console.log('1111');
             //   attriAttachment.push(data);
             //   console.log(attriAttachment);
             //   console.log('678');
             // }
           }
          // this.attriAttachment = attriAttachment;
          // console.log(this.attriAttachment);
          this.loading = false;
          // this.$toast.success('提交成功');
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
