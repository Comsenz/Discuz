/**
 * 角色权限编辑
 */

import Card from "../../../../view/site/common/card/card";
import CardRow from "../../../../view/site/common/card/cardRow";

export default {
  data: function() {
    return {
      groupId: 0, // 用户组 ID
      checked: [], // 选中的权限
      videoDisabled: false, // 是否开启云点播
      captchaDisabled: false, // 是否开启验证码
      realNameDisabled: false, // 是否开启实名认证
      is_subordinate: false, // 是否开启推广下线
      is_commission: false, // 是否开启分成
      scale: 0, // 提成比例
      bindPhoneDisabled: false, // 是否开启短信验证
      wechatPayment: false, // 是否开启微信支付
      canBeOnlooker: false, // 是否可以设置围观
      categoriesList: [], // 分类列表
      selectList: {
        'viewThreads': [],
        'createThread':[],
        'thread.reply':[],
        'thread.edit':[],
        'thread.hide':[],
        'thread.essence':[],
        'thread.viewPosts':[],
        'thread.editPosts':[],
        'thread.hidePosts':[],
        'thread.canBeReward': [],
        'thread.editOwnThreadOrPost': [],
        'thread.hideOwnThreadOrPost': [],
        'thread.freeViewPosts.1':[],
        'thread.freeViewPosts.2':[],
        'thread.freeViewPosts.3':[],
        'thread.freeViewPosts.4':[],
        'thread.freeViewPosts.5':[],
      },
      activeTab: {
        // 设置权限当前项
        title: "操作权限",
        name: "userOperate"
      },
      menuData: [
        // 设置权限
        {
          title: "操作权限",
          name: "userOperate"
        },
        {
          title: "安全设置",
          name: "security"
        },
        {
          title: "价格设置",
          name: "pricesetting"
        },
        {
          title: "其他设置",
          name: "other"
        }
        // {
        //   title: '默认权限',
        //   name: 'default'
        // },
      ],
      value: "",
      purchasePrice: "",
      dyedate: "",
      ispad: "",
      allowtobuy: "",
      defaultuser: false,
      checkAll: false, //是否全选
      isIndeterminate: false,//全选不确定状态
      selectText: '全选', //全选文字
      checkAllPermission: [], //所有操作权限
      temporaryChecked: [], //接口返回权限
    };
  },
  watch: {
    checked(val){
      let isEqual = true;
      this.checkAllPermission.forEach(item => {
        if(val.indexOf(item) === -1){
          isEqual = false;
          return;
        }
      });
      if(isEqual){
        this.checkAll = true;
      }else{
        this.checkAll = false;
      }
    },
    checkAll(val){
      if(val){
        this.isIndeterminate = false;
        this.selectText = "取消全选";
      } else {
        this.isIndeterminate = true;
        this.selectText = "全选";
      }
    }
  },
  methods: {
    duedata: function(evn) {
      this.duedata = evn.replace(/[^\d]/g, "");
    },
    addprice: function(evn) {
      setTimeout(() => {
        this.purchasePrice = evn
          .replace(/[^\d.]/g, "")
          .replace(/\.{2,}/g, ".")
          .replace(".", "$#$")
          .replace(/\./g, "")
          .replace("$#$", ".")
          .replace(/^(-)*(\d+)\.(\d\d).*$/, "$1$2.$3")
          .replace(/^\./g, "");
      }, 5);
    },
    signUpSet() {
      this.appFetch({
        url: "forum",
        method: "get"
      }).then(res => {
        if (res.errors) {
          this.$message.error(res.errors[0].code);
        } else {
          this.videoDisabled = res.readdata._data.qcloud.qcloud_vod === false;
          this.captchaDisabled =
            res.readdata._data.qcloud.qcloud_captcha === false;
          this.realNameDisabled =
            res.readdata._data.qcloud.qcloud_faceid === false;
          this.bindPhoneDisabled =
            res.readdata._data.qcloud.qcloud_sms === false;
          this.wechatPayment =
            res.readdata._data.paycenter.wxpay_close === false;
          this.canBeOnlooker =
            res.readdata._data.set_site.site_onlooker_price > 0;
          this.allowtobuy = res.readdata._data.set_site.site_pay_group_close;
          if (!this.allowtobuy) {
            this.value = false;
          }
        }
      });
    },

    /**
     * 获取所有分类
     */
    getCategories() {
      this.appFetch({
        url: "categories",
        method: "get"
      }).then(res => {
        if (res.errors) {
          this.$message.error(res.errors[0].code);
        } else {
          this.categoriesList = [{ id: "", name: "全局" }];
          res.readdata.forEach(item => {
            let category = {
              id: item._data.id,
              name: item._data.name
            };
            this.categoriesList.push(category);
          });
        }
      });
    },

    submitClick() {
      if (!this.checkNum()) {
        return;
      }
      if (!this.checkSelect()) {
        return;
      }
      if (this.value) {
        if (this.purchasePrice == 0) {
          this.$message.error("价格不能为0");
          return;
        } else if (this.purchasePrice == " ") {
          this.$message.error("价格不能为空");
          return;
        } else if (this.dyedate == 0) {
          this.$message.error("到期时间不能为0");
          return;
        } else if (this.dyedate == " ") {
          this.$message.error("到期时间不能为空");
          return;
        } else {
          this.patchGroupScale();
        }
      } else {
        this.patchGroupScale();
      }
    },

    /*
     * 接口请求
     * */
    getGroupResource() {
      this.appFetch({
        url: "groups",
        method: "get",
        splice: "/" + this.groupId,
        data: {
          include: ["permission", "categoryPermissions"]
        }
      })
        .then(res => {
          if (res.errors) {
            if (res.errors[0].detail) {
              this.$message.error(
                res.errors[0].code + "\n" + res.errors[0].detail[0]
              );
            } else {
              this.$message.error(res.errors[0].code);
            }
          } else {
            this.ispad = res.data.attributes.isPaid;
            this.purchasePrice = res.data.attributes.fee;
            this.dyedate = res.data.attributes.days;
            let data = res.readdata.permission;
            this.checked = [];
            this.scale = res.data.attributes.scale;
            this.is_subordinate = res.data.attributes.is_subordinate;
            this.is_commission = res.data.attributes.is_commission;
            this.defaultuser = res.data.attributes.default;
            this.value = res.data.attributes.isPaid;
            this.temporaryChecked = res.readdata.permission;
            data.forEach(item => {
              this.checked.push(item._data.permission);
            });
            // 下拉值回显
            this.setSelectValue(this.checked);
          }
        })
        .catch(err => {});
    },
    patchGroupPermission() {
      let checked = this.checked;
      if (this.is_commission || this.is_subordinate) {
        if (checked.indexOf("other.canInviteUserScale") === -1) {
          checked.push("other.canInviteUserScale");
        }
      } else {
        checked = checked.filter(v => v !== "other.canInviteUserScale");
      }
      this.appFetch({
        url: "groupPermission",
        method: "post",
        data: {
          data: {
            attributes: {
              groupId: this.groupId,
              permissions: checked
            }
          }
        }
      })
        .then(res => {
          if (res.errors) {
            this.$message.error(res.errors[0].code);
          } else {
            this.$message({
              showClose: true,
              message: "提交成功",
              type: "success"
            });
          }
        })
        .catch(err => {});
    },

    patchGroupScale() {
      this.appFetch({
        url: "groups",
        method: "PATCH",
        splice: "/" + this.groupId,
        data: {
          data: {
            attributes: {
              name: this.$route.query.name,
              is_paid: this.value ? 1 : 0,
              fee: this.purchasePrice,
              days: this.dyedate,
              scale: this.scale,
              is_subordinate: this.is_subordinate,
              is_commission: this.is_commission
            }
          }
        }
      })
        .then(res => {
          if (res.errors) {
            this.$message.error(res.errors[0].code);
          } else {
            this.patchGroupPermission();
          }
        })
        .catch(err => {});
    },

    handlePromotionChange(value) {
      this.is_subordinate = value;
    },
    handleScaleChange(value) {
      this.is_commission = value;
    },
    checkNum() {
      if (!this.scale) {
        return true;
      }
      const reg = /^([0-9](\.\d)?|10)$/;
      if (!reg.test(this.scale)) {
        this.$message({
          message: "提成比例必须是0~10的整数或者一位小数",
          type: "error"
        });
        return false;
      }
      return true;
    },
    // 下拉改变
    changeCategory(obj, value) {
      let checked = this.checked;
      const item = `category${value}.${obj}`;
      // 是否选的是全局
      if (!value) {
        // 选中全局就去除其他勾选
        for (let i = 0; i < checked.length; i++) {
          if (
            checked[i].indexOf(obj) !== -1 &&
            checked[i].indexOf("category") !== -1
          ) {
            checked.splice(i, 1);
            i = i - 1;
          }
        }
        if (checked.indexOf(obj) === -1) checked.push(obj);
        this.selectList[obj] = [""];
      } else {
        // 在下拉选中数组里面
        if (this.selectList[obj].indexOf(value) !== -1) {
          checked.push(item);
        } else {
          // 不在下拉选中数组中就去除此权限
          checked = checked.filter(v => v !== item);
        }
        // 选中其他的就去除全局的权限
        checked = checked.filter(v => v !== obj);
        this.selectList[obj] = this.selectList[obj].filter(v => !!v);
      }
      this.checked = checked;
    },
    setSelectValue(data) {
      const checkedData = data;
      const selectList = this.selectList;
      const selectItem = [
        'viewThreads',
        'createThread',
        'thread.reply',
        'thread.edit',
        'thread.hide',
        'thread.essence',
        'thread.viewPosts',
        'thread.editPosts',
        'thread.hidePosts',
        'thread.canBeReward',
        'thread.editOwnThreadOrPost',
        'thread.hideOwnThreadOrPost',
        'thread.freeViewPosts.1',
        'thread.freeViewPosts.2',
        'thread.freeViewPosts.3',
        'thread.freeViewPosts.4',
        'thread.freeViewPosts.5',
      ];
      checkedData.forEach((value, index) => {
        // 全局的回显
        if (selectItem.indexOf(value) !== -1) {
          selectList[value].push("");
        }
        // 分类的回显
        if (value.indexOf("category") !== -1) {
          const splitIndex = value.indexOf(".");
          const obj = value.substring(splitIndex + 1);
          const id = value.substring(8, splitIndex);
          if (selectList[obj] && checkedData.indexOf(obj) === -1) {
            selectList[obj].push(id);
          }
          if (checkedData.indexOf(obj) !== -1) {
            checkedData.splice(index, 1);
          }
        }
      });
      this.selectList = selectList;
      this.checked = checkedData;
    },
    // 清除某项下拉
    clearItem(value, obj) {
      let item = "";
      if (value) {
        item = `category${value}.${obj}`;
      } else {
        item = obj;
      }
      let checkedData = this.checked;
      checkedData = checkedData.filter(v => v !== item);
      this.checked = checkedData;
    },
    changeChecked(value, obj) {
      if (!value) {
        const checkedData = this.checked;
        this.selectList[obj] = [];
        this.checked = checkedData.filter(v => v.indexOf(obj) === -1);
      }
    },
    //全选/取消全选
    handleCheckAllChange(val) {
      if (val) {
        this.checkAllPermission.forEach(item => {
          if(this.checked.indexOf(item) == -1){
            this.checked.push(item);
          }
        })
        this.setSelectValue(this.checked);
        this.checkAll = true;
      } else {
        this.checked = [];
        // this.temporaryChecked.forEach(item => {
        //   this.checked.push(item._data.permission);
        // });
        this.selectList = {
          'viewThreads': [],
          'createThread':[],
          'thread.reply':[],
          'thread.edit':[],
          'thread.hide':[],
          'thread.essence':[],
          'thread.viewPosts':[],
          'thread.editPosts':[],
          'thread.hidePosts':[],
          'thread.canBeReward': [],
          'thread.editOwnThreadOrPost': [],
          'thread.hideOwnThreadOrPost': [],
          'thread.freeViewPosts.1':[],
          'thread.freeViewPosts.2':[],
          'thread.freeViewPosts.3':[],
          'thread.freeViewPosts.4':[],
          'thread.freeViewPosts.5':[],
        };
        this.checkAll = false;
      }
    },
    checkSelect() {
      if (this.checked.indexOf('switch.createThread') !== -1) {
        if(this.selectList.createThread.length === 0){
          this.$message.error("请选择发布主题权限");
          return false;
        }
      }
      if (this.checked.indexOf('switch.thread.reply') !== -1) {
        if(this.selectList['thread.reply'].length === 0){
          this.$message.error("请选择回复主题权限");
          return false;
        }
      }
      if (this.checked.indexOf('switch.thread.canBeReward') !== -1) {
        if(this.selectList['thread.canBeReward'].length === 0){
          this.$message.error("请选择允许被打赏权限");
          return false;
        }
      }
      if (this.checked.indexOf('switch.viewThreads') !== -1) {
        if(this.selectList.viewThreads.length === 0){
          this.$message.error("请选择查看主题列表权限");
          return false;
        }
      }
      if (this.checked.indexOf('switch.thread.viewPosts') !== -1) {
        if(this.selectList['thread.viewPosts'].length === 0){
          this.$message.error("请选择查看主题详情权限");
          return false;
        }
      }
      if (this.checked.indexOf('switch.thread.freeViewPosts.1') !== -1) {
        if(this.selectList['thread.freeViewPosts.1'].length === 0){
          this.$message.error("请选择免费查看付费帖子权限");
          return false;
        }
      }
      if (this.checked.indexOf('switch.thread.freeViewPosts.2') !== -1) {
        if(this.selectList['thread.freeViewPosts.2'].length === 0){
          this.$message.error("请选择免费查看付费视频权限");
          return false;
        }
      }
      if (this.checked.indexOf('switch.thread.freeViewPosts.3') !== -1) {
        if(this.selectList['thread.freeViewPosts.3'].length === 0){
          this.$message.error("请选择免费查看付费图片权限");
          return false;
        }
      }
      if (this.checked.indexOf('switch.thread.freeViewPosts.4') !== -1) {
        if(this.selectList['thread.freeViewPosts.4'].length === 0){
          this.$message.error("请选择免费查看付费语音权限");
          return false;
        }
      }
      if (this.checked.indexOf('switch.thread.freeViewPosts.5') !== -1) {
        if(this.selectList['thread.freeViewPosts.5'].length === 0){
          this.$message.error("请选择免费查看付费问答权限");
          return false;
        }
      }
      if (this.checked.indexOf('switch.thread.essence') !== -1) {
        if(this.selectList['thread.essence'].length === 0){
          this.$message.error("请选择加精权限");
          return false;
        }
      }
      if (this.checked.indexOf('switch.thread.edit') !== -1) {
        if(this.selectList['thread.edit'].length === 0){
          this.$message.error("请选择编辑主题权限");
          return false;
        }
      }
      if (this.checked.indexOf('switch.thread.hide') !== -1) {
        if(this.selectList['thread.hide'].length === 0){
          this.$message.error("请选择删除主题权限");
          return false;
        }
      }
      if (this.checked.indexOf('switch.thread.editPosts') !== -1) {
        if(this.selectList['thread.editPosts'].length === 0){
          this.$message.error("请选择编辑回复权限");
          return false;
        }
      }
      if (this.checked.indexOf('switch.thread.hidePosts') !== -1) {
        if(this.selectList['thread.hidePosts'].length === 0){
          this.$message.error("请选择删除回复权限");
          return false;
        }
      }
      if (this.checked.indexOf('switch.thread.editOwnThreadOrPost') !== -1) {
        if(this.selectList['thread.editOwnThreadOrPost'].length === 0){
          this.$message.error("请选择编辑自己的主题或回复权限");
          return false;
        }
      }
      if (this.checked.indexOf('switch.thread.hideOwnThreadOrPost') !== -1) {
        if(this.selectList['thread.hideOwnThreadOrPost'].length === 0){
          this.$message.error("请选择删除自己的主题或回复权限");
          return false;
        }
      }
      return true;
    }
  },
  created() {
    this.groupId = this.$route.query.id;
    this.activeTab.title = this.$route.query.title || "操作权限";
    this.activeTab.name = this.$route.query.names || "userOperate";
    this.getGroupResource();
    this.signUpSet();
    this.getCategories();
    if (this.groupId === '7') {
      this.checkAllPermission = [
        "switch.viewThreads", //查看主题列表
        "switch.thread.viewPosts", //查看主题详情
        "switch.thread.freeViewPosts.1", //免费查看付费帖子
        "switch.thread.freeViewPosts.2", //免费查看付费视频
        "switch.thread.freeViewPosts.3", //免费查看付费图片
        "switch.thread.freeViewPosts.4", //免费查看付费语音
        "switch.thread.freeViewPosts.5", //免费查看付费问答
      ];
    } else {
      this.checkAllPermission = [
        "createThread.0", //发布文字帖
        "createThread.1", //发布帖子
        "createThread.2", //发布视频帖
        "createThread.3", //发布图片帖
        "createThread.4", //发布语音帖
        "createThread.5", //发布问答
        "createThread.6", //发布商品帖
        "dialog.create", //发布私信
        "canBeAsked", //允许被提问
        "canBeOnlooker", //设置围观
        "attachment.create.0", //上传附件
        "attachment.create.1", //上传图片
        "createThreadPaid", //发布付费内容
        "switch.createThread", //发布主题
        "switch.thread.reply", //回复主题
        "switch.thread.canBeReward", //允许被打赏
        "switch.viewThreads", //查看主题列表
        "switch.thread.viewPosts", //查看主题详情
        "switch.thread.freeViewPosts.1", //免费查看付费帖子
        "switch.thread.freeViewPosts.2", //免费查看付费视频
        "switch.thread.freeViewPosts.3", //免费查看付费图片
        "switch.thread.freeViewPosts.4", //免费查看付费语音
        "switch.thread.freeViewPosts.5", //免费查看付费问答
        "thread.sticky", //置顶
        "createInvite", //邀请加入
        "user.edit.group", //编辑用户组
        "user.edit.status", //编辑用户状态
        "switch.thread.essence", //加精
        "switch.thread.edit", //编辑主题
        "switch.thread.hide", //删除主题
        "switch.thread.editPosts", //编辑回复
        "switch.thread.hidePosts", //删除回复
        "switch.thread.editOwnThreadOrPost", //编辑自己的主题或回复
        "switch.thread.hideOwnThreadOrPost", //删除自己的主题或回复
      ];
    }
  },
  components: {
    Card,
    CardRow
  }
};
