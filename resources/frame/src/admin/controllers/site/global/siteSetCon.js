import Card from "../../../view/site/common/card/card";
import CardRow from "../../../view/site/common/card/cardRow";

export default {
  data: function() {
    return {
      radio: "1",
      radio2: "2",
      // fileList:[],
      loading: true,
      fullscreenLoading: false,
      siteName: "",
      siteIntroduction: "",
      siteKeywords: "",
      siteTitle: "",
      siteMode: "1", //站点模式选择
      sitePrice: "",
      siteExpire: "",
      siteAuthorScale: "",
      siteMasterScale: "",
      siteClose: "1", //关闭站点选择
      // siteLogoFile: {},
      siteLogoFile: [],
      siteMasterId: "",
      siteRecord: "",
      recodeNumber: "",
      siteStat: "",
      siteCloseMsg: "",
      dialogImageUrl: "",
      dialogVisible: false,
      fileList: [],
      deleBtn: false,
      disabled: true, // 付费模式置灰
      askPrice: "", // 问答围观价格
      purchase: false, // 权限购买
      purchaseNum: 0,
      numberimg: [
        {
          imageUrl: "",
          imgWidht: 0,
          imgHeight: 0,
          text: "站点LOGO",
          textrule: "推荐高度：88px"
        },
        {
          imageUrl: "",
          imgWidht: 0,
          imgHeight: 0,
          text: "首页头部LOGO",
          textrule: "推荐高度：88px"
        },
        {
          imageUrl: "",
          imgWidht: 0,
          imgHeight: 0,
          text: "首页头部背景",
          textrule: "尺寸：750px*400px"
        },
        {
          imageUrl: "",
          imgWidht: 0,
          imgHeight: 0,
          text: "ICON",
          textrule: "尺寸：120px*120px"
        }
      ]
    };
  },

  created: function() {
    //初始化请求设置
    this.loadStatus();
  },
  computed: {
    // uploadDisabled:function() {
    //     return this.fileList.length >0
    // },
  },

  methods: {
    loadStatus() {
      //初始化设置
      this.appFetch({
        url: "forum",
        method: "get",
        data: {}
      })
        .then(data => {
          if (data.errors) {
            this.$message.error(data.errors[0].code);
          } else {
            console.log("11111");
            // 微信支付关闭时置灰付费模式
            if (data.readdata._data.paycenter.wxpay_close == false) {
              this.disabled = true;
            } else {
              this.disabled = false;
            }
            //
            this.siteName = data.readdata._data.set_site.site_name;
            this.siteIntroduction =
              data.readdata._data.set_site.site_introduction;
            this.siteKeywords = data.readdata._data.set_site.site_keywords;
            this.siteTitle = data.readdata._data.set_site.site_title;
            this.siteMode = data.readdata._data.set_site.site_mode;
            this.numberimg[0].imageUrl = data.readdata._data.set_site.site_logo;
            this.numberimg[1].imageUrl =
              data.readdata._data.set_site.site_header_logo;
            this.numberimg[2].imageUrl =
              data.readdata._data.set_site.site_background_image;
            // icon
            this.numberimg[3].imageUrl =
              data.readdata._data.set_site.site_favicon;
            if (this.siteMode == "pay") {
              this.radio = "2";
            } else {
              this.radio = "1";
            }
            this.sitePrice = data.readdata._data.set_site.site_price;
            this.siteExpire = data.readdata._data.set_site.site_expire;
            this.siteAuthorScale =
              data.readdata._data.set_site.site_author_scale;
            this.siteMasterScale =
              data.readdata._data.set_site.site_master_scale;
            // this.siteLogoFile = data.readdata._data.siteLogoFile;
            this.siteRecord = data.readdata._data.set_site.site_record;
            this.recodeNumber = data.readdata._data.set_site.site_record_code;
            this.siteStat = data.readdata._data.set_site.site_stat;

            if (
              data.readdata._data.set_site.site_author &&
              data.readdata._data.set_site.site_author.id
            ) {
              this.siteMasterId = data.readdata._data.set_site.site_author.id;
            }

            this.askPrice = data.readdata._data.set_site.site_onlooker_price;
            // if (data.readdata._data.logo) {
            //   this.fileList.push({url: data.readdata._data.logo});
            // }
            this.siteClose = data.readdata._data.set_site.site_close;
            if (this.siteClose === true) {
              this.radio2 = "1";
            } else {
              this.radio2 = "2";
            }

            this.siteCloseMsg = data.readdata._data.set_site.site_close_msg;
            this.purchase = !!data.readdata._data.set_site.site_pay_group_close;
            // 微信支付关闭时置灰付费模式
            if (data.readdata._data.paycenter.wxpay_close == false) {
              this.disabled = true;
            } else {
              this.disabled = false;
            }
            this.getScaleImgSize(this.numberimg[0].imageUrl, {
              width: 140,
              height: 140
            }).then(res => {
              this.numberimg[0].imgWidht = res.width;
              this.numberimg[0].imgHeight = res.height;
            });
            this.getScaleImgSize(this.numberimg[1].imageUrl, {
              width: 140,
              height: 140
            }).then(res => {
              this.numberimg[1].imgWidht = res.width;
              this.numberimg[1].imgHeight = res.height;
            });
            this.getScaleImgSize(this.numberimg[2].imageUrl, {
              width: 140,
              height: 140
            }).then(res => {
              this.numberimg[2].imgWidht = res.width;
              this.numberimg[2].imgHeight = res.height;
            });
            this.getScaleImgSize(this.numberimg[3].imageUrl, {
              width: 140,
              height: 140
            }).then(res => {
              this.numberimg[3].imgWidht = res.width;
              this.numberimg[3].imgHeight = res.height;
            });
          }
        })
        .catch(error => {});
    },
    //删除已上传logo
    deleteImage(file, index, fileList) {
      let type = "";
      switch (index) {
        case 0:
          type = "logo";
          break;
        case 1:
          type = "header_logo";
          break;
        case 2:
          type = "background_image";
          break;
        case 3:
          type = "favicon";
          break;
        default:
          this.$message.error("未知类型");
      }
      this.numberimg[index].imageUrl = "";
      this.appFetch({
        url: "logo",
        method: "delete",
        data: {
          type: type
        }
      })
        .then(data => {
          if (data.errors) {
            this.$message.error(data.errors[0].code);
          } else {
            this.$message("删除成功");
          }
        })
        .catch(error => {});
    },
    handlePictureCardPreview(file) {
      this.dialogImageUrl = file.url;
      this.dialogVisible = true;
    },
    radioChange(siteMode) {
      this.siteMode = siteMode;
    },
    radioChangeClose(closeVal) {
      if (closeVal == "1") {
        this.siteClose = true;
      } else {
        this.siteClose = false;
      }
    },
    handleAvatarSuccess(res, file) {
      // this.imageUrl = URL.createObjectURL(file.raw);
    },
    handleFile() {},
    getScaleImgSize(url, obj) {
      if (url === "") {
        return;
      }
      //处理等比例上传图片，
      return new Promise((resolve, reject) => {
        this.getImageSize(url)
          .then(res => {
            const scale = res.height / res.width;
            if (scale > obj.height / obj.width) {
              resolve({
                width: obj.height / scale,
                height: obj.height
              });
            } else {
              resolve({
                width: obj.width,
                height: obj.width * scale
              });
            }
          })
          .catch(err => {
            console.log(err);
            // reject(err);
          });
      });
    },
    getImageSize(url) {
      const img = document.createElement("img");
      return new Promise((resolve, reject) => {
        img.onload = ev => {
          resolve({ width: img.naturalWidth, height: img.naturalHeight });
        };
        img.src = url;
        img.onerror = reject;
        console.log(url);
      });
    },

    //上传时，判断文件的类型及大小是否符合规则
    beforeAvatarUpload(file) {
      const isJPG =
        file.type == "image/jpeg" ||
        file.type == "image/png" ||
        file.type == "image/gif" ||
        file.type == "image/ico";
      const isLt2M = file.size / 1024 / 1024 < 2;
      if (!isJPG) {
        this.$message.warning("上传头像图片只能是 JPG/PNG/GIF/ICO 格式!");
        return isJPG;
      }
      if (!isLt2M) {
        this.$message.warning("上传头像图片大小不能超过 2MB!");
        return isLt2M;
      }
      return isJPG && isLt2M;
    },
    // 上传图片
    uploaderLogo(e, index) {
      let type = "";
      switch (index) {
        case 0:
          type = "logo";
          break;
        case 1:
          type = "header_logo";
          break;
        case 2:
          type = "background_image";
          break;
        case 3:
          type = "favicon";
          break;
        default:
          this.$message.error("未知类型");
      }
      let logoFormData = new FormData();
      logoFormData.append("logo", e.file);
      logoFormData.append("type", type);
      this.appFetch({
        url: "logo",
        method: "post",
        data: logoFormData
      })
        .then(data => {
          if (data.errors) {
            this.$message.error(data.errors[0].code);
          } else {
            this.numberimg[index].imageUrl = data.readdata._data.default.logo;
            this.getScaleImgSize(this.numberimg[index].imageUrl, {
              width: 140,
              height: 140
            }).then(res => {
              this.numberimg[index].imgWidht = res.width;
              this.numberimg[index].imgHeight = res.height;
            });
            this.$message({ message: "上传成功", type: "success" });
          }
        })
        .catch(error => {});
    },
    siteSetPost() {
      this.appFetch({
        url: "settings",
        method: "post",
        data: {
          data: [
            {
              attributes: {
                key: "site_name",
                value: this.siteName ? this.siteName : "",
                tag: "default"
              }
            },
            {
              attributes: {
                key: "site_introduction",
                value: this.siteIntroduction ? this.siteIntroduction : "",
                tag: "default"
              }
            },
            {
              attributes: {
                key: "site_keywords",
                value: this.siteKeywords ? this.siteKeywords : "",
                tag: "default"
              }
            },
            {
              attributes: {
                key: "site_title",
                value: this.siteTitle ? this.siteTitle : "",
                tag: "default"
              }
            },
            {
              attributes: {
                key: "site_author",
                value: this.siteMasterId,
                tag: "default"
              }
            },
            {
              attributes: {
                key: "site_mode",
                value: this.siteMode,
                tag: "default"
              }
            },
            {
              attributes: {
                key: "site_price",
                value: this.sitePrice,
                tag: "default"
              }
            },
            {
              attributes: {
                key: "site_expire",
                value: this.siteExpire,
                tag: "default"
              }
            },
            {
              attributes: {
                key: "site_author_scale",
                value: this.siteAuthorScale,
                tag: "default"
              }
            },
            {
              attributes: {
                key: "site_master_scale",
                value: this.siteMasterScale,
                tag: "default"
              }
            },
            {
              attributes: {
                key: "site_record",
                value: this.siteRecord,
                tag: "default"
              }
            },
            {
              attributes: {
                key: "site_record_code",
                value: this.recodeNumber,
                tag: "default"
              }
            },
            {
              attributes: {
                key: "site_stat",
                value: this.siteStat,
                tag: "default"
              }
            },
            {
              attributes: {
                key: "site_close",
                value: this.siteClose,
                tag: "default"
              }
            },
            {
              attributes: {
                key: "site_close_msg",
                value: this.siteCloseMsg,
                tag: "default"
              }
            },
            {
              attributes: {
                key: "site_onlooker_price",
                value: this.askPrice,
                tag: "default"
              }
            },
            {
              attributes: {
                key: "site_pay_group_close",
                value: this.purchase,
                tag: "default"
              }
            }
          ]
        }
      })
        .then(data => {
          if (data.errors) {
            if (data.errors[0].detail) {
              this.$message.error(
                data.errors[0].code + "\n" + data.errors[0].detail[0]
              );
            } else {
              this.$message.error(data.errors[0].code);
            }
          } else {
            this.$message({
              message: "提交成功",
              type: "success"
            });
          }
        })
        .catch(error => {});
    },
    onblurFun() {
      if (this.siteAuthorScale == null || this.siteAuthorScale == "") {
        this.siteAuthorScale = 0;
      }
      if (this.siteMasterScale == null || this.siteMasterScale == "") {
        this.siteMasterScale = 0;
      }
      var countRes =
        parseFloat(this.siteAuthorScale) + parseFloat(this.siteMasterScale);
      if (countRes != 10) {
        this.$message({
          message: "分成比例相加必须为10",
          type: "error"
        });
      }
    }
  },
  components: {
    Card,
    CardRow
  }
};
