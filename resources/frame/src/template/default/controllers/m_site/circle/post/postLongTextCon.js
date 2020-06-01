/**
 * 发布主题控制器
 */
import { autoTextarea, debounce } from '../../../../../../common/textarea.js';
import appCommonH from '../../../../../../helpers/commonHelper';
import browserDb from '../../../../../../helpers/webDbHelper';
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
      publishShow: true,     //是否触发验证码的按钮
      appID: '',             //腾讯云验证码场景 id
      captcha: null,         //腾讯云验证码实例
      captcha_ticket: '',    //腾讯云验证码返回票据
      captcha_rand_str: '',  //腾讯云验证码返回随机字符串
      payBtnDis: false,
      loading: false,
      isWeixinUpload: false
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
    this.appID = browserDb.getLItem('siteInfo')._data.qcloud.qcloud_captcha_app_id;
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
    this.initWxUpload();
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
    getInfo() {
      //请求站点信息，用于判断是否能上传附件
      this.$store.dispatch("appSiteModule/loadForum").then(res => {
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
              imgStrRes = '.' + ImgExt[k] + ',';
              this.supportImgExt += ImgStr;
              this.supportImgExtRes += imgStrRes;
            }
            this.supportImgExtRes = 'image/*,' + this.supportImgExtRes;
            this.supportImgExtRes = this.supportImgExtRes.substring(0, this.supportImgExtRes.length - 1);
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
        this.appFetch({
          url: 'threads',
          splice: '/' + this.themeId,
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
      this.loading = true;
      if (this.postsId && this.content) {
        this.appFetch({
          url: 'posts',
          splice: '/' + this.postsId,
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
            this.loading = false;
          } else {
            this.$router.replace({ path: 'details' + '/' + this.themeId, query: { backGo: this.backGo } });
          }
        })
      } else {
        if (this.themeTitle.length < 3) {
          this.$toast.fail('标题不得少于三个字符');
          this.loading = false;
          return false;
        }
        if (this.content.length < 1) {
          this.$toast.fail('内容不得为空');
          this.loading = false;
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
            this.loading = false;
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
        this.$toast('已达上传图片数量上限');
        return;
      }
      let maxUpload = 12 - this.fileListOne.length;
      let uploadCount = 0;
      files.map((file, index) => {
        if (this.testingType(file.file, this.supportImgExt)) {
          uploadCount ++;
          if (uploadCount > maxUpload) {
            return;
          }
          this.loading = true;
          this.compressFile(file.file, 150000, false, index, files.length - index);
        }
      });
    },

    //上传附件
    handleEnclosure(e) {
      if (this.testingType(e.target.files[0], this.supportFileExt)) {
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
        this.loading = false;
        return false;
      }
      return true;
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
          this.loading = false;
          throw new Error(data.error);

        } else {
          if (img) {
            this.loading = false;
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
            this.loading = false;
          }
        }
      })
    },

    //压缩图片
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
        that.uploaderEnclosure(formdata, uploadShow, !uploadShow, false, index);
        // that.loading = false;
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
            });
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
        this.paySetValue = Number(this.paySetValue);
        this.payValue = Number(this.paySetValue) + '元';
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
      this.captcha = new TencentCaptcha(this.appID, res => {
        if (res.ret === 0) {
          this.captcha_ticket = res.ticket;
          this.captcha_rand_str = res.randstr;
          //验证通过后注册
          this.publish();
        }
      });
      // 显示验证码
      this.captcha.show();
    },
    // 初始化微信上传
    checkWxReady() {
      return new Promise((resolve, reject) => {
        wx.ready(() => resolve())
        wx.error(err => reject(err))
      });
    },

    initWxUpload() {
      if (this.isWeixin) {
        let url = window.location.protocol + '//' + window.location.hostname + (window.location.port ? ':' + window.location.port : '') + this.$route.path;
        if (this.isiOS && window.entryUrl && !/wechatdevtools/.test(navigator.userAgent)) { // iOS下，URL必须设置为整个SPA的入口URL
          url = window.entryUrl;
        }
        this.appFetch({
          url: 'weChatShare',
          method: 'get',
          data: {
            url
          }
        }).then((res) => {
          let appId = res.readdata._data.appId;
          let nonceStr = res.readdata._data.nonceStr;
          let signature = res.readdata._data.signature;
          let timestamp = res.readdata._data.timestamp;
          this.checkWxReady().then(() => {
            this.isWeixinUpload = true;
          }).catch(err => {
            this.isWeixinUpload = false;
          });
          wx.config({
            debug: false,          // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
            appId: appId,         // 必填，公众号的唯一标识
            timestamp: timestamp, // 必填，生成签名的时间戳
            nonceStr: nonceStr,   // 必填，生成签名的随机串
            signature: signature, // 必填，签名，见附录1
            jsApiList: [
              'chooseImage',
              'getLocalImgData'
            ]
          });
          this.isWeixinUpload = true;
        });
      }
    },
    weixinUpload() {
      const self = this;
      let maxUpload = 12 - this.fileListOne.length;
      if (maxUpload > 9) maxUpload = 9;
      wx.chooseImage({
        count: maxUpload,
        success: (res) => {
          this.loading = true;
          var localIds = res.localIds;
          localIds.forEach(function(lId, index) {
            wx.getLocalImgData({
              localId: lId,
              success: function(res) {
                let localData = res.localData;
                if (localData.indexOf('data:image') != 0) {
                  //判断是否有这样的头部
                  localData = 'data:image/jpeg;base64,' +  localData
                }
                let imageBase64 = localData.replace(/\r|\n/g, '').replace(/data:image\/jpg/i, 'data:image/jpeg')
                let blob = self.base64ToBlob(imageBase64);
                let formdata = new FormData();
                formdata.append('file', blob, index + ".jpg");
                formdata.append('type', 1);
                formdata.append('order', index);
                self.uploaderEnclosure(formdata, false, true, false, index);
              }
            });
          });
        }
      });
      return false;
    },
    base64ToBlob(dataurl) {
      let arr = dataurl.split(',');
      let mime = arr[0].match(/:(.*?);/)[1];
      let bstr = atob(arr[1]);
      let n = bstr.length;
      let u8arr = new Uint8Array(n);
      while (n--) {
          u8arr[n] = bstr.charCodeAt(n);
      }
      return new Blob([u8arr], { type: mime });
    }
  },
  beforeRouteLeave(to, from, next) {
    // 隐藏验证码
    if (this.captcha) {
      this.captcha.destroy();
    }
    next();
  }
}
