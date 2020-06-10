/**
 * 发布主题控制器
 */
import { debounce, autoTextarea } from '../../../../../../common/textarea.js';
import '@github/markdown-toolbar-element';
import appCommonH from '../../../../../../helpers/commonHelper';
let rootFontSize = parseFloat(document.documentElement.style.fontSize);
export default {
  data: function () {
    return {
      headerTitle: "编辑主题",
      selectSort: '',
      showPopup: false,
      categories: [],
      categoriesId: [],
      oldCateId: '',
      cateId: '',
      content: '',
      showFacePanel: false,
      keyboard: false,
      // expressionShow: false,
      keywordsMax: 1000,
      list: [],
      footMove: false,
      payMove: false,
      markMove: false,
      faceData: [],
      fileListOne: [],
      fileList: [
        // Uploader 根据文件后缀来判断是否为图片文件
        // 如果图片 URL 中不包含类型信息，可以添加 isImage 标记来声明
        // { url: 'https://cloud-image', isImage: true }
      ],
      uploadShow: false,
      enclosureList: [
        // {
        //   type:'doc',
        //   name:'saaaaaaaa',
        // },
      ],
      avatar: "",
      postsId: '',
      files: {
        name: "",
        type: ""
      },
      headerImage: null,
      picValue: null,
      upImgUrl: '',
      enclosureShow: false,
      isWeixin: false,
      isPhone: false,
      themeCon: false,
      attriAttachment: false,
      fileLength: 0,
      canUploadImages: '',
      canUploadAttachments: '',
      supportImgExt: '',
      supportImgExtRes: '',
      supportFileExt: '',
      supportFileArr: '',
      limitMaxLength: true,
      limitMaxEncLength: true,
      fileListOneLen: '',
      enclosureListLen: '',
      isiOS: false,
      encuploadShow: false,
      testingRes: false,
      backGo: -2,
      formdataList: [],
      themeTitle: '',   //标题
      payValue: '免费',     //长文价格
      paySetShow: false,
      isCli: true,
      moneyVal: '',
      timeout: null,
      paySetValue: '',
      titleMaxLength: 80,
      viewportWidth: '',
      viewportHeight: '',
      loading: false,
    }
  },

  mounted() {
    window.addEventListener('scroll', this.handleScroll, true)
    this.focus('themeTitle');
    let postForm = document.getElementById('postForm');
    postForm.style.height = (this.viewportHeight) + 'px';

    // let text = document.getElementById('textarea_id');
    // text.addEventListener("touchstart",(e)=>{
    //   // alert('触发');
    //   // this.showFacePanel = false;this.footMove = false;this.keyboard = false;

    //   let textarea = this.$refs.textarea;
    //   // textarea.focus();
    //   let prevHeight = 300;
    //   textarea && autoTextarea(textarea, 5, 65535, (height) => {
    //     height += 20;
    //     if (height !== prevHeight) {
    //       prevHeight = height;
    //       let rem = height / rootFontSize;
    //       // this.$refs.list.style.height = `calc(100% - ${rem}rem)`;
    //     }
    //   });
    // });


    this.$nextTick(() => {
      let textarea = this.$refs.textarea;
      // textarea.focus();
      let prevHeight = 300;
      textarea && autoTextarea(textarea, 5, 65535, (height) => {
        height += 20;
        this.$refs.postForm.scrollTop = this.$refs.textarea.clientHeight - this.viewportHeight + 400;
        this.$refs.postForm.scrollTop += this.$refs.postForm.scrollTop + 20;
        if (height !== prevHeight) {
          prevHeight = height;
          let rem = height / rootFontSize;
          // this.$refs.list.style.height = `calc(100% - ${rem}rem)`;
        }
      });
    })
    //设置在pc的宽度
    if (this.isWeixin != true && this.isPhone != true) {
      // this.limitWidth();
    }
  },
  computed: {
    themeId: function () {
      return this.$route.params.themeId;
    }
  },
  created() {
    this.viewportWidth = window.innerWidth;
    this.viewportHeight = window.innerHeight;
    this.isWeixin = appCommonH.isWeixin().isWeixin;
    this.isPhone = appCommonH.isWeixin().isPhone;
    var u = navigator.userAgent;
    this.isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1; //android终端
    this.isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端

    if (this.isiOS) {
      this.encuploadShow = true;
    }
    //初始化请求分类接口
    this.loadCategories();
    //初始化请求主题数据
    this.detailsLoad();
    this.getInfo();
  },
  watch: {
    'fileListOne.length': function (newVal, oldVal) {
      this.fileListOneLen = newVal;
      if (this.fileListOneLen >= 12) {
        this.limitMaxLength = false;
      } else {
        this.limitMaxLength = true;
      }
    },
    'enclosureList.length': function (newVal, oldVal) {
      this.enclosureListLen = newVal;
      if (this.enclosureListLen >= 3) {
        this.limitMaxEncLength = false;
      } else {
        this.limitMaxEncLength = true;
      }
    },
    showFacePanel: function (newVal, oldVal) {
      this.showFacePanel = newVal;
      if (this.showFacePanel) {
        document.getElementById('postForm').style.height = (this.viewportHeight - 340) + 'px';
      } else {
        document.getElementById('postForm').style.height = '100%';
      }
    },
  },
  methods: {
    formatter(value) {
      return this.handleReg(value);
    },

    handleReg(value) {
      value = value.toString(); // 先转换成字符串类型

      if (value.indexOf('.') == 0) {
        value = '0.';  // 第一位就是 .
      }

      value = value.replace(/[^\d.]/g, "");  //清除“数字”和“.”以外的字符
      value = value.replace(/\.{2,}/g, "."); //只保留第一个. 清除多余的
      value = value.replace(".", "$#$").replace(/\./g, "").replace("$#$", ".");
      value = value.replace(/^(\-)*(\d+)\.(\d\d).*$/, '$1$2.$3');//只能输入两个小数

      //以上已经过滤，此处控制的是如果没有小数点，首位不能为类似于 01、02的金额
      if (value.indexOf(".") < 0 && value != "") {
        value = parseFloat(value);
      }

      return value;
    },


    focus(obj) {
      document.getElementById(obj).focus();
    },
    //请求站点信息，用于判断是否能上传附件
    getInfo() {
      this.$store.dispatch("appSiteModule/loadForum").then(res => {
        if (res.errors) {
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        } else {
          var ImgExt = res.readdata._data.set_attach.support_img_ext.split(',');
          var ImgStr = '';
          var imgStrRes = '';
          for (var k = 0; k < ImgExt.length; k++) {
            ImgStr = '.' + ImgExt[k] + ',';
            imgStrRes = '.' + ImgExt[k] + ',';
            this.supportImgExt += ImgStr;
            this.supportImgExtRes += imgStrRes;
          }
          this.supportImgExtRes = 'image/*,' + this.supportImgExtRes;
          this.supportImgExtRes = this.supportImgExtRes.substring(0, this.supportImgExtRes.length - 1);

          var fileExt = res.readdata._data.set_attach.support_file_ext.split(',');
          var fileStr = '';
          for (var k = 0; k < fileExt.length; k++) {
            fileStr = '.' + fileExt[k] + ',';
            this.supportFileExt += fileStr;
          }
          this.canUploadImages = res.readdata._data.other.can_upload_images;
          this.canUploadAttachments = res.readdata._data.other.can_upload_attachments;
        }
      });
    },
    //初始化请求编辑主题数据
    detailsLoad() {
      this.appFetch({
        url: 'threads',
        method: 'get',
        splice: '/' + this.themeId,
        data: {
          include: ['firstPost', 'firstPost.images', 'firstPost.attachments', 'category'],
        }
      }).then((res) => {
        if (res.errors) {
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        } else {
          var enclosureListCon = res.readdata.firstPost.attachments;
          var fileListCon = res.readdata.firstPost.images;
          this.oldCateId = res.readdata.category._data.id;
          this.selectSort = res.readdata.category._data.name;
          this.content = res.readdata.firstPost._data.content;
          this.themeTitle = res.readdata._data.title;
          if (res.readdata._data.price > 0) {
            this.payValue = res.readdata._data.price;
            this.paySetValue = res.readdata._data.price;
            this.isCli = true;
          }

          this.postsId = res.readdata.firstPost._data.id;
          for (let i = 0; i < enclosureListCon.length; i++) {
            this.enclosureList.push({ type: enclosureListCon[i]._data.extension, name: enclosureListCon[i]._data.fileName, id: enclosureListCon[i]._data.id });
          }
          if (this.enclosureList.length > 0) {
            this.enclosureShow = true;
          }
          for (var i = 0; i < fileListCon.length; i++) {
            // this.fileListOne.push({thumbUrl:fileListCon[i]._data.thumbUrl,id:fileListCon[i]._data.id});
            this.fileListOne.push({ url: fileListCon[i]._data.thumbUrl, id: fileListCon[i]._data.id });
          }

          if (this.fileListOne.length > 0) {
            this.uploadShow = true;
          }
        }
      })
    },
    //发布主题
    publish() {
      this.attriAttachment = this.fileListOne.concat(this.enclosureList);
      for (let m = 0; m < this.attriAttachment.length; m++) {
        this.attriAttachment[m] = {
          "type": "attachments",
          "id": this.attriAttachment[m].id
        }
      }
      this.loading = true;
      if (this.oldCateId != this.cateId) {
        this.appFetch({
          url: 'threads',
          method: "patch",
          splice: '/' + this.themeId,
          data: {
            "data": {
              "type": "threads",
              "attributes": {
                "price": this.paySetValue,
                "title": this.themeTitle,
              },
              "relationships": {
                "category": {
                  "data": {
                    "type": "categories",
                    "id": this.cateId
                  }
                }
              }
            }
          },
        }).then((res) => {
          if (res.errors) {
            this.loading = false;
            this.$toast.fail(res.errors[0].code);
            throw new Error(res.error)
          } else {
            // this.$router.push({ path:'/details'+'/'+this.themeId});
          }
        })
      }
      this.appFetch({
        url: 'posts',
        method: "patch",
        splice: '/' + this.postsId,
        data: {
          "data": {
            "type": "threads",
            "attributes": {
              "content": this.content,
            },

            "relationships": {
              "attachments": {
                "data": this.attriAttachment
              },
            }
          }
        },
      }).then((res) => {
        if (res.errors) {
          this.loading = false;
          this.$toast.fail(res.errors[0].code + '\n' + res.errors[0].detail[0]);
          throw new Error(res.error)
        } else {
          this.$router.replace({ path: '/details' + '/' + this.themeId });
        }
      })
    },

    //上传之前先判断是否有权限上传图片
    beforeHandleFile() {
      if (!this.canUploadImages) {
        this.$toast.fail('没有上传图片的权限');
      } else {
        if (!this.limitMaxLength) {
          this.$toast.fail('已达上传图片上限');
        }
      }
    },

    beforeHandleEnclosure() {
      if (!this.canUploadAttachments) {
        this.$toast.fail('没有上传附件的权限');
      } else {
        if (!this.limitMaxEncLength) {
          this.$toast.fail('已达上传附件上限');
        }
      }
    },
    //上传图片,点击加号时
    handleFile(e) {
      let files = [];
      if (e.length === undefined) {
        files.push(e);
      } else {
        files = e;
      }
      if (!this.limitMaxLength) {
        this.$toast.fail('已达上传图片上限');
      } else {
        files.map((file, index) => {
          if (this.isAndroid && this.isWeixin) {
            this.testingType(file.file, this.supportImgExt);
            if (this.testingRes) {
              this.loading = true;
              this.compressFile(file.file, 150000, false, this.fileListOne.length + index, files.length - index);
            }
          } else {
            this.loading = true;
            this.compressFile(file.file, 150000, false, this.fileListOne.length + index, files.length - index);
          }
        });
      }
    },

    //上传图片，点击底部Icon时
    handleFileUp(e) {
      let fileListNowLen = e.target.files.length + this.fileListOne.length <= 12 ? e.target.files.length : 12 - this.fileListOne.length;
      for (var i = 0; i < fileListNowLen; i++) {
        var file = e.target.files[i];
        if (this.isAndroid && this.isWeixin) {
          this.testingType(file, this.supportImgExt);
          if (this.testingRes) {
            this.loading = true;
            this.compressFile(file, 150000, true, this.fileListOne.length + i, file.length - i);
          }
        } else {
          this.loading = true;
          this.compressFile(file, 150000, true, this.fileListOne.length + i, file.length - i);
        }
      }
    },
    //上传附件
    handleEnclosure(e) {
      this.testingType(e.target.files[0], this.supportFileExt);
      if (this.testingRes) {
        let file = e.target.files[0];
        let formdata = new FormData();
        formdata.append('file', file);
        formdata.append('type', 0);
        this.loading = true;
        this.uploaderEnclosure(formdata, false, false, true);
      }

    },

    //验证上传格式是否符合设置
    testingType(eFile, allUpext) {
      let extName = eFile.name.substring(eFile.name.lastIndexOf(".")).toLowerCase();
      let AllUpExt = allUpext;
      if (AllUpExt.indexOf(extName + ",") == "-1") {
        this.$toast.fail("文件类型不允许!");
        this.testingRes = false;
        // return false;
      } else {
        this.testingRes = true;
      }
    },


    // 删除图片
    deleteEnclosure(id, type) {
      if (this.fileListOne.length < 1) {
        this.uploadShow = false;
      }
      this.appFetch({
        url: 'attachment',
        method: 'delete',
        splice: '/' + id.id,
      })
    },

    // 删除附件
    deleteEnc(id, type) {
      if (this.fileListOne.length < 1) {
        this.uploadShow = false;
      }
      this.appFetch({
        url: 'attachment',
        method: 'delete',
        splice: '/' + id.id
      }).then(data => {
        var newArr = this.enclosureList.filter(item => item.id !== id.id);
        this.enclosureList = newArr;

      })
    },

    // 这里写接口，上传
    uploaderEnclosure(file, isFoot, img, enclosure, index) {
      this.appFetch({
        url: 'attachment',
        method: 'post',
        data: file,
      }).then(data => {
        if (data.errors) {
          this.$toast.fail(data.errors[0].code);
          this.loading = false;
          throw new Error(data.error)
        } else {
          if (img) {
            this.fileList.push({ url: data.readdata._data.url, id: data.readdata._data.id });
            this.fileListOne[index - 1].id = data.data.attributes.id;
            this.loading = false;
          }
          if (isFoot) {
            this.fileListOne.push({ url: data.readdata._data.url, id: data.readdata._data.id });
            // 当上传一个文件成功 时，显示组件，否则不处理
            if (this.fileListOne.length > 0) {
              this.uploadShow = true;
              this.loading = false;
            }
          }
          if (enclosure) {
            this.enclosureShow = true
            this.enclosureList.push({
              type: data.readdata._data.extension,
              name: data.readdata._data.fileName,
              id: data.readdata._data.id
            });
            this.loading = false;
          }

        }
      })
    },

    //压缩图片
    // compressFile(file, uploadShow, wantedSize = 150000, event){
    compressFile(file, wantedSize, uploadShow, index, indexSum) {
      const curSize = file.size || file.length * 0.8
      const quality = Math.max(wantedSize / curSize, 0.8)
      let that = this;
      lrz(file, {
        quality: 0.8, //设置压缩率
      }).then(function (rst) {
        let formdata = new FormData();
        formdata.append('file', rst.file, file.name);
        formdata.append('type', 1);
        formdata.append('order', index);
        // that.uploaderEnclosure(formdata, uploadShow, !uploadShow);
        that.uploaderEnclosure(formdata, uploadShow, !uploadShow, false, index);
        that.loading = false;
      }).catch(function (err) {
        /* 处理失败后执行 */
      }).always(function () {
        /* 必然执行 */
      })
    },

    //输入框自适应高度
    clearKeywords() {
      this.keywords = '';
      this.list = [];
      let textarea = this.$refs.textarea;
      let height = 40;
      let rem = height / rootFontSize;
      textarea.style.height = `${rem}rem`;
      rem = (height + 20) / rootFontSize;
      this.$refs.postForm.scrollTop = textarea.style.height - 1000;
      // this.$refs.list.style.height = `calc(100% - ${rem}rem)`;
      // textarea.focus();
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
    handleFaceChoose(face) {
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
    addExpression() {
      let text = document.getElementById('postForm');

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
      if (this.showFacePanel) {
        document.getElementById('postForm').style.height = (this.viewportHeight - 340) + 'px';
        text.style.paddingBottom = '50px';
      } else {
        document.getElementById('postForm').style.height = '100%';
        text.style.paddingBottom = '150px';
      }
      this.footMove = !this.footMove;
      this.payMove = !this.payMove;
      this.markMove = !this.markMove;
    },
    backClick() {
      this.$router.go(-1);
    },
    dClick() {
      this.showPopup = true;
    },
    onConfirm(value, index) {
      var id = value.id;
      this.cateId = id;
      var text = value.text;
      this.showPopup = false;
      this.selectSort = value.text;
    },

    loadCategories() {
      this.appFetch({
        url: 'categories',
        method: 'get',
        data: {
          include: '',
        }
      }).then((res) => {

        if (res.errors) {
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        } else {
          var newCategories = [];
          newCategories = res.readdata;
          for (let j = 0, len = newCategories.length; j < len; j++) {
            this.categories.push(
              {
                'text': newCategories[j]._data.name,
                'id': newCategories[j]._data.id
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
    paySetting() {
      this.paySetShow = true;

      if (this.payValue === '免费') {
        this.paySetValue = null;
      } else {
        this.paySetValue = this.payValue.slice(0, this.payValue.length - 1);
      }

      if (this.paySetShow) {
        setTimeout(function () {
          document.getElementById('payMoneyInp').focus();
        }, 200);
      }
    },
    //关闭付费设置弹框
    closePaySet() {
      this.paySetShow = false;
      // this.paySetValue = '免费';
    },
    //设置付费时，实时获取输入框的值，用来判断按钮状态
    search: function (event) {

      if (this.paySetValue === '.') {                // 如果只输入一个点  变成 0.
        this.paySetValue = '0.';
        return;
      }

      // if(event.target.value != null && event.target.value > '0'){
      //   this.isCli = true;
      // } else {
      //   this.isCli = false;
      // }
    },
    //点击确定按钮，提交付费设置
    paySetSure() {
      this.paySetShow = false;
      if (this.paySetValue <= 0) {
        this.payValue = '免费';
      } else {
        this.paySetValue = Number(this.paySetValue);
        this.payValue = Number(this.paySetValue) + '元';
      }
    },

  }
}
