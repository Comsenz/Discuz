/**
 * 发布主题控制器
 */
import { debounce, autoTextarea } from '../../../../../common/textarea.js';
import appCommonH from '../../../../../helpers/commonHelper';
import browserDb from '../../../../../helpers/webDbHelper';
import '@github/markdown-toolbar-element';
let rootFontSize = parseFloat(document.documentElement.style.fontSize);
export default {
  data: function () {
    return {
      headerTitle: "发布长文",
      selectSort: '选择分类',
      showPopup: false,
      categories: [],
      categoriesId: [],
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
      fileList: [
        // Uploader 根据文件后缀来判断是否为图片文件
        // 如果图片 URL 中不包含类型信息，可以添加 isImage 标记来声明
        // { url: 'https://ss1.bdstatic.com/70cFuXSh_Q1YnxGkpoWK1HF6hhy/it/u=88704046,262850083&fm=11&gp=0.jpg', isImage: true }
      ],
      fileListOne: [],
      uploadShow: false,
      enclosureList: [],
      avatar: "",
      themeId: '',
      postsId: '',
      files: {
        name: "",
        type: '',
      },
      headerImage: null,
      picValue: null,
      upImgUrl: '',
      enclosureShow: false,
      isWeixin: false,
      isPhone: false,
      themeCon: false,
      attriAttachment: false,
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
      viewportWidth: '',
      themeTitle: '',
      payValue: '免费',
      paySetShow: false,
      isCli: true,
      moneyVal: '',
      timeout: null,
      paySetValue: '',
      titleMaxLength: 80,
      viewportHeight: '',
      publishShow: true, //是否触发验证码的按钮
      appID: '',         //腾讯云验证码场景 id
      captcha_ticket: '',    //腾讯云验证码返回票据
      captcha_rand_str: ''   //腾讯云验证码返回随机字符串

    }
  },
  computed: {
    nowCateId: function () {
      return this.$route.params.cateId;
    }
  },
  mounted() {
    this.focus('themeTitle');
    this.$nextTick(() => {
      let textarea = this.$refs.textarea;
      // textarea.focus();
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
    if (this.isWeixin != true && this.isPhone != true) {
      // this.limitWidth();
    }
  },
  created() {
    this.viewportWidth = window.innerWidth;
    this.viewportHeight = window.innerHeight;
    this.isWeixin = appCommonH.isWeixin().isWeixin;
    this.isPhone = appCommonH.isWeixin().isPhone;
    let qcloud_captcha = browserDb.getLItem('siteInfo')._data.qcloud.qcloud_captcha;
    let thread_captcha = browserDb.getLItem('siteInfo')._data.other.create_thread_with_captcha;
    if (qcloud_captcha && thread_captcha) {
      this.publishShow = false
    } else {
      this.publishShow = true
    }
    var u = navigator.userAgent;
    this.isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1; //android终端
    this.isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端

    if (this.isiOS) {
      this.encuploadShow = true;
    }
    if (this.$route.params.themeId) {
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
    // 'limitMaxLength': function(newVal,oldVal){
    //   this.limitMaxLength = newVal;
    // },
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
    themeTitle() {
      if (this.themeTitle.length > this.titleMaxLength) {
        this.themeTitle = String(this.themeTitle).slice(0, this.titleMaxLength);
      }
    },
  },
  methods: {
    focus(obj) {
      document.getElementById(obj).focus();
    },
    getInfo() {
      //请求站点信息，用于判断是否能上传附件
      this.appFetch({
        url: 'forum',
        method: 'get',
        data: {
          include: ['users'],
        },
      }).then((res) => {
        if (res.errors) {
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        } else {
          var ImgExt = '';
          if (res.readdata._data.set_attach.support_img_ext) {
            ImgExt = res.readdata._data.set_attach.support_img_ext.split(',');
            var ImgStr = '';
            var imgStrRes = '';
            for (var k = 0; k < ImgExt.length; k++) {
              ImgStr = '.' + ImgExt[k] + ',';
              imgStrRes = 'image/' + ImgExt[k] + ',';
              this.supportImgExt += ImgStr;
              this.supportImgExtRes += imgStrRes;
            }
          } else {
            ImgExt = '*';
          }

          var fileExt = '';
          if (res.readdata._data.set_attach.support_file_ext) {
            fileExt = res.readdata._data.set_attach.support_file_ext.split(',');
            var fileStr = '';
            for (var k = 0; k < fileExt.length; k++) {
              fileStr = '.' + fileExt[k] + ',';
              this.supportFileExt += fileStr;
            }
          } else {
            fileExt = '*';
          }

          this.canUploadImages = res.readdata._data.other.can_upload_images;
          this.canUploadAttachments = res.readdata._data.other.can_upload_attachments;
        }
      });
    },
    //初始化请求编辑主题数据
    detailsLoad() {
      if (this.postsId && this.content) {
        let threads = 'threads/' + this.themeId;
        this.appFetch({
          url: threads,
          method: 'get',
          data: {
            include: ['firstPost', 'firstPost.images', 'firstPost.attachments', 'category'],
          }
        }).then((res) => {
          if (res.errors) {
            this.$toast.fail(res.errors[0].code);
            throw new Error(res.error)
          } else {
            // this.enclosureList = res.readdata.attachments;
            // this.fileList = res.readdata.images;
            const initializeCateId = res.readdata.category._data.id;
            this.selectSort = res.readdata.category._data.description;
            if (this.cateId != initializeCateId) {
              this.cateId = initializeCateId;
            }
          }

        })
      }
    },
    //发布长文
    publish() {
      if (this.themeTitle == '' || this.themeTitle == null) {
        this.$toast.fail('标题不能为空');
        return;
      }
      if (this.content == '' || this.content == null) {
        this.$toast.fail('内容不能为空');
        return;
      }
      if (this.cateId == 0 || this.cateId == undefined) {
        this.$toast.fail('请选择分类');
        return;
      }
      if (this.postsId && this.content) {
        let posts = 'posts/' + this.postsId;
        this.appFetch({
          url: posts,
          method: "patch",
          data: {
            "data": {
              "type": "posts",
              "attributes": {
                "type": 1,
                "content": this.content,
                "captcha_ticket": this.captcha_ticket,
                "captcha_rand_str": this.captcha_rand_str
              }
            }
          }
        }).then((res) => {
          if (res.errors) {
            if (res.errors[0].detail) {
              this.$toast.fail(res.errors[0].code + '\n' + res.errors[0].detail[0])
            } else {
              this.$toast.fail(res.errors[0].code);
            }
          } else {
            this.$router.replace({ path: 'details' + '/' + this.themeId, query: { backGo: this.backGo } });
          }
        })
      } else {
        if (this.themeTitle.length < 3) {
          this.$toast.fail('标题不得少于三个字符');
          return false;
        }
        if (this.content.length < 1) {
          this.$toast.fail('内容不得为空');
          return false;
        }

        this.attriAttachment = this.fileListOne.concat(this.enclosureList);
        for (let m = 0; m < this.attriAttachment.length; m++) {
          this.attriAttachment[m] = {
            "type": "attachments",
            "id": this.attriAttachment[m].id
          }
        }

        this.appFetch({
          url: "threads",
          method: "post",
          data: {
            "data": {
              "type": "threads",
              "attributes": {
                "price": this.paySetValue,
                "title": this.themeTitle,
                "type": 1,
                "content": this.content,
                "captcha_ticket": this.captcha_ticket,
                "captcha_rand_str": this.captcha_rand_str
              },
              "relationships": {
                "category": {
                  "data": {
                    "type": "categories",
                    "id": this.cateId
                  }
                },
                "attachments": {
                  "data": this.attriAttachment
                },
              }

            }
          },
        }).then((res) => {
          if (res.errors) {
            if (res.errors[0].detail) {
              this.$toast.fail(res.errors[0].code + '\n' + res.errors[0].detail[0])
            } else {
              this.$toast.fail(res.errors[0].code);
            }
          } else {
            var postThemeId = res.readdata._data.id;
            var _this = this;
            _this.$router.replace({ path: '/details' + '/' + postThemeId, query: { backGo: this.backGo } });
          }
        })
      }
    },

    // //设置底部在pc里的宽度
    // limitWidth(){
    //   document.getElementById('post-topic-footer').style.width = "640px";
    //   let viewportWidth = window.innerWidth;
    //   document.getElementById('post-topic-footer').style.left = (viewportWidth - 640)/2+'px';
    // },

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
              this.compressFile(file.file, 150000, false, files.length - index);
            }
          } else {
            this.compressFile(file.file, 150000, false, files.length - index);
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
            this.compressFile(file, 150000, true);
          }
        } else {
          this.compressFile(file, 150000, true);
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
        formdata.append('isGallery', 0);
        this.uploaderEnclosure(formdata, false, false, true);
      }

    },

    //验证上传格式是否符合设置
    testingType(eFile, allUpext) {
      let extName = eFile.name.substring(eFile.name.lastIndexOf(".")).toLowerCase();
      let AllUpExt = allUpext;
      if (AllUpExt.indexOf(extName + ",") == "-1") {
        this.$toast.fail("文件格式不正确!");
        this.testingRes = false;
        // return false;
      } else {
        this.testingRes = true;
      }
    },
    getAllEvens(arr) {
      arr => {
        let temp = evens(arr);
        return flat(temp);
      }
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
          throw new Error(data.error)
        } else {
          if (img) {
            this.fileList.push({ url: data.readdata._data.url, id: data.readdata._data.id });
            this.fileListOne[this.fileListOne.length - index].id = data.data.attributes.id;
          }
          if (isFoot) {
            this.fileListOne.push({ url: data.readdata._data.url, id: data.readdata._data.id });
            // 当上传一个文件成功 时，显示组件，否则不处理
            if (this.fileListOne.length > 0) {
              this.uploadShow = true;
            }

          }
          if (enclosure) {
            this.enclosureShow = true
            this.enclosureList.push({
              type: data.readdata._data.extension,
              name: data.readdata._data.fileName,
              id: data.readdata._data.id
            });
          }
          this.loading = false;
        }
      })
    },

    //压缩图片
    compressFile(file, wantedSize, uploadShow, index) {
      const curSize = file.size || file.length * 0.8
      const quality = Math.max(wantedSize / curSize, 0.8)
      let that = this;
      lrz(file, {
        quality: 0.8, //设置压缩率
      }).then(function (rst) {
        let formdata = new FormData();
        formdata.append('file', rst.file, file.name);
        formdata.append('isGallery', 1);
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
      this.footMove = !this.footMove;
      this.payMove = !this.payMove;
      this.markMove = !this.markMove;
      if (this.showFacePanel) {
        document.getElementById('postForm').style.height = (this.viewportHeight - 340) + 'px';
      } else {
        document.getElementById('postForm').style.height = '100%';
      }

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
    //分类接口
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
          if (this.nowCateId != 0 && this.nowCateId != undefined) {
            var nowCate = {};
            nowCate = newCategories.find((item) => {
              if (item._data.id === this.nowCateId) {
                return item
              }
            })
            this.nowCate = { id: nowCate._data.id, name: nowCate._data.name };
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
    paySetting() {
      this.paySetShow = true;
      if (this.paySetShow) {
        setTimeout(function () {
          document.getElementById('payMoneyInp').focus();
        }, 200);
      }
    },
    //关闭付费设置弹框
    closePaySet() {
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
    paySetSure() {
      this.paySetShow = false;
      if (this.paySetValue <= 0) {
        this.payValue = '免费';
      } else {
        this.payValue = this.paySetValue + '元';
      }

    },
    initCaptcha() {   //验证码
      if (this.themeTitle == '' || this.themeTitle == null) {
        this.$toast.fail('标题不能为空');
        return;
      }
      if (this.content == '' || this.content == null) {
        this.$toast.fail('内容不能为空');
        return;
      }
      if (this.cateId == 0 || this.cateId == undefined) {
        this.$toast.fail('请选择分类');
        return;
      }
      let tct = new TencentCaptcha(this.appID, res => {
        if (res.ret === 0) {
          this.captcha_ticket = res.ticket;
          this.captcha_rand_str = res.randstr;
          //验证通过后注册
          this.publish();
        }
      })
      // 显示验证码
      tct.show();
    }

  },
  /*beforeRouteEnter(to,from,next){

    next(vm =>{
      if (from.name === 'circle'){
        vm.backGo = -2
      }
    });
  }*/
}
