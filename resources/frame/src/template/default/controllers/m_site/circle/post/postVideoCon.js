/**
 * 发布视频主题控制器
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
  data: function () {
    return {
      headerTitle: "发布视频",
      selectSort: '',
      showPopup: false,
      categories: [],
      categoriesId: [],
      cateId: '',
      content: '',
      showFacePanel: false,
      keyboard: false,
      keywordsMax: 10000,
      list: [],
      footMove: false,
      payMove: false,
      faceData: [],
      uploadShow: false,
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
      isWeixin: false,
      isPhone: false,
      themeCon: false,
      attriAttachment: false,
      canUploadImages: '',
      canUploadAttachments: '',
      supportVideoExt: '',
      supportVideoExtRes: '',
      fileSize: '',
      limitMaxLength: true,
      limitMaxEncLength: true,
      isiOS: false,
      encuploadShow: false,
      testingRes: false,
      backGo: -2,
      formdataList: [],
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
      testingSizeRes: false,
      testingTypeRes: false,
      fileId: '',
      loading: false, //是否处于加载状态
      publishShow: '',
      appID: '',              // 腾讯云验证码场景 id
      captcha: null,          // 腾讯云验证码实例
      captcha_ticket: '',     // 腾讯云验证码返回票据
      captcha_rand_str: '',   // 腾讯云验证码返回随机字符串

    }
  },
  computed: {
    nowCateId: function () {
      return this.$route.params.cateId;
    }
  },
  mounted() {
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
    if (this.isWeixin != true && this.isPhone != true) {
      this.limitWidth();
    }
  },
  created() {
    this.tcVod = new TcVod({
      getSignature: getSignature
    });
    let qcloud_captcha = browserDb.getLItem('siteInfo')._data.qcloud.qcloud_captcha;
    let thread_captcha = browserDb.getLItem('siteInfo')._data.other.create_thread_with_captcha;
    this.appID = browserDb.getLItem('siteInfo')._data.qcloud.qcloud_captcha_app_id;
    this.publishShow = !(qcloud_captcha && thread_captcha);
    // console.log(this.tcVod,'343423');
    var videoExt = '';
    if (browserDb.getLItem('siteInfo') && browserDb.getLItem('siteInfo')._data.qcloud.qcloud_vod_ext) {
      this.fileSize = browserDb.getLItem('siteInfo')._data.qcloud.qcloud_vod_size;
      videoExt = browserDb.getLItem('siteInfo')._data.qcloud.qcloud_vod_ext.split(',');
      var videoStr = '';
      var videoStrRes = '';
      for (var k = 0; k < videoExt.length; k++) {
        videoStr = '.' + videoExt[k] + ',';
        // videoStrRes = 'video/' + videoExt[k] + ',';
        videoStrRes = '.'+ videoExt[k] + ',';
        this.supportVideoExt += videoStr;
        this.supportVideoExtRes += videoStrRes;
      }
      this.supportVideoExtRes = 'video/*,' + this.supportVideoExtRes;
      this.supportVideoExtRes = this.supportVideoExtRes.substring(0,this.supportVideoExtRes.length - 1);
    } else {
      videoExt = '*';
    }
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
    //初始化请求forum
    this.getInfo();


  },
  watch: {
    showFacePanel: function (newVal, oldVal) {
      this.showFacePanel = newVal;
      if (this.showFacePanel) {
        document.getElementById('postForm').style.height = (this.viewportHeight - 240) + 'px';
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


    vExampleAdd: function () {
      this.$refs.vExampleFile.click();
      // this.$refs.vcExampleCover.click();
    },
    //添加封面
    // vcExampleAddCover: function() {
    //   this.$refs.vcExampleCover.click();
    // },
    //验证上传格式是否符合设置
    testingType(eFile, allUpext) {
      let extName = eFile.name.substring(eFile.name.lastIndexOf(".")).toLowerCase();
      let AllUpExt = allUpext;
      if (AllUpExt.indexOf(extName + ",") == "-1") {
        this.$toast.fail("文件类型不允许!");
        this.testingTypeRes = false;
        this.loading = false;
        // return false;
      } else {
        this.testingTypeRes = true;
      }
    },
    //验证上传文件大小是否符合设置
    testingSize(eFile, allowSize) {
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
      this.testingType(e.target.files[0], this.supportVideoExt);
      this.testingSize(e.target.files[0], this.fileSize);
      if (this.testingSizeRes && this.testingTypeRes) {
        this.loading = true;
        var self = this;
        var mediaFile = this.$refs.vExampleFile.files[0];
        this.vcVideoName = this.$refs.vExampleFile.files[0].name;
        var uploader = this.tcVod.upload({
          mediaFile: mediaFile,
          // coverFile: coverFile,
        });
        uploader.on("media_progress", function (info) {
          uploaderInfo.progress = info.percent;
        });
        uploader.on("media_upload", function (info) {
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
          cancel: function () {
            uploaderInfo.isVideoUploadCancel = true;
            uploader.cancel();
          }
        };

        this.uploaderInfos.push(uploaderInfo);

        uploader
          .done()
          .then((doneResult) => {
            // console.log("doneResult", doneResult);
            uploaderInfo.fileId = doneResult.fileId;
            this.videoUp = false;
            this.loading = false;
            this.videoShow = true;
            this.fileId = doneResult.fileId;
            // console.log('要提交的视频id',this.fileId);
          })
          .then(function (videoUrl) {
            uploaderInfo.videoUrl = videoUrl;
            self.$refs.vExample.reset();
          });
      }

    },

    setVcExampleCoverName: function () {
      this.vcExampleCoverName = this.$refs.vcExampleCover.files[0].name;
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

          this.canUploadImages = res.readdata._data.other.can_upload_images;
          this.canUploadAttachments = res.readdata._data.other.can_upload_attachments;
        }
      });
    },

    //删除视频
    videoDeleClick() {
      this.videoShow = false;
      this.videoUp = true;
      this.fileId = '';
    },
    //发布主题
    publish() {
      if (this.content == '' || this.content == null) {
        this.$toast.fail('内容不能为空');
        return;
      }
      if (this.cateId == 0 || this.cateId == undefined) {
        this.$toast.fail('请选择分类');
        return;
      }
      if (this.vcVideoName == '') {
        this.$toast.fail('视频不能为空');
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
                "content": this.content
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
            // console.log('主题');
            this.$router.replace({ path: 'details' + '/' + this.themeId, query: { backGo: this.backGo }, replace: true });
          }
        })
      } else {
        this.appFetch({
          url: "threads",
          method: "post",
          data: {
            "data": {
              "type": "threads",
              "attributes": {
                "content": this.content,
                "price": this.paySetValue,
                'file_id': this.fileId,
                'file_name': this.vcVideoName,
                'type': 2,
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
            // console.log('视频');
            _this.$router.replace({ path: '/details' + '/' + postThemeId, query: { backGo: this.backGo }, replace: true });
          }
        })
      }
    },

    //设置底部在pc里的宽度
    limitWidth() {
      document.getElementById('post-topic-footer').style.width = "640px";
      let viewportWidth = window.innerWidth;
      document.getElementById('post-topic-footer').style.left = (viewportWidth - 640) / 2 + 'px';
    },

    getAllEvens(arr) {
      arr => {
        let temp = evens(arr);
        return flat(temp);
      }
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
    //表情
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
      if (this.showFacePanel) {
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
    onConfirm(value, index) {
      // console.log(value,'====================');
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
        console.log(this.paySetValue);
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
    initCaptcha() {   //发布主题验证码
      if (this.content == '' || this.content == null) {
        this.$toast.fail('内容不能为空');
        return;
      }
      if (this.cateId == 0 || this.cateId == undefined) {
        this.$toast.fail('请选择分类');
        return;
      }
      if (this.vcVideoName == '') {
        this.$toast.fail('视频不能为空');
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
