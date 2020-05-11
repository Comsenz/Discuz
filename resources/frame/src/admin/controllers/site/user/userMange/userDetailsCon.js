/*
* 用户详情
* */

import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';
import browserDb from '../../../../../helpers/webDbHelper';

export default {
  data: function () {
    return {
      fileList: [],
      options: [],
      optionsList: [],
      imageUrl: '',
      userRole: [],
      userInfo: {},
      newPassword: '',
      wechatNickName: '',
      sex: '',
      disabled: false,
      disabledReason: false,
      reasonsForDisable: '',//禁用原用
      realname: '', //实名认证是否显示
      optionsStatus: [
        {
          value: 0,
          label: '正常'
        },
        {
          value: 1,
          label: '禁用'
        },
        {
          value: 2,
          label: '审核'
        }
      ],
      value: '',
      query: {},
      deleBtn: false,
    }
  },

  created() {
    this.query = this.$route.query;
    this.getUserDetail();
    this.getUserList()
  },

  methods: {
    async getUserDetail() {
      try {
        var userId = browserDb.getLItem('tokenId');
        const response = await this.appFetch({
          method: 'get',
          url: 'users',
          splice: `/${this.query.id}`,
          data: {
            include: 'wechat,groups'
          }
        })
        if (response.errors) {
          this.$message.error(response.errors[0].code);
        } else {
          this.userInfo = response.readdata._data;
          this.imageUrl = this.userInfo.avatarUrl;
          if (this.imageUrl != '' && this.imageUrl != null) {
            this.deleBtn = true;
          }
          this.reasonsForDisable = this.userInfo.banReason;
          this.userRole = response.readdata.groups.map((v) => {
            return v._data.id
          });
          if (response.readdata.wechat) {
            this.wechatNickName = response.readdata.wechat._data.nickname
            this.sex = response.readdata.wechat._data.sex
          }
          if (userId == this.userInfo.id) {
            this.disabled = true;
          }
          if (this.userInfo.status == 1) {
            this.disabledReason = true
          }
          if (this.userInfo.realname == '') {
            this.realname = false;
          } else {
            this.realname = true;
          }
        }

      } catch (err) {
      }
    },
    handleRemove(file, fileList) {
    },
    deleteImage() {
      if (this.deleBtn == false) {
        return
      }
      this.imageUrl = '';
      this.appFetch({
        url: 'deleteAvatar',
        method: 'delete',
        splice: `/${this.query.id}` + '/avatar',
        data: {

        }
      }).then(res => {
        if (res.errors) {
          this.$message.error(res.errors[0].code);
        } else {
          this.deleBtn = false;
          this.$message.success('删除成功');
        }
      })
    },
    handlePreview(file) {
    },
    handleExceed(files, fileList) {
      this.$message.warning(`当前限制选择 3 个文件，本次选择了 ${files.length} 个文件，共选择了 ${files.length + fileList.length} 个文件`);
    },
    beforeRemove(file, fileList) {
      return this.MessageBox.confirm(`确定移除 ${file.name}？`);
    },
    handleAvatarSuccess(res, file) {
      // this.imageUrl = URL.createObjectURL(file.raw);
    },
    handleFile() {

    },
    beforeAvatarUpload(file) {
      const isJPG = file.type === 'image/jpeg';
      const isLt10M = file.size / 1024 / 1024 < 10;

      if (!isJPG) {
        this.$message.error('上传头像图片只能是 JPG 格式!');
      }
      if (!isLt10M) {
        this.$message.error('上传头像图片大小不能超过 10MB!');
      }
      if (isJPG && isLt10M == true) {
      }
      return isJPG && isLt10M;
    },
    uploaderLogo(file) {
      let formData = new FormData()
      formData.append('avatar', file.file)
      this.appFetch({
        url: 'upload',
        method: 'post',
        splice: `${this.query.id}` + '/avatar',
        data: formData
      }).then(res => {
        if (res.errors) {
          this.$message.error(res.errors[0].code);
        } else {
          this.$message.success('上传成功');
          this.imageUrl = res.readdata._data.avatarUrl;
          this.deleBtn = true;
        }
      })
    },

    submission() {
      var reg = 11 && /^((13|14|15|16|17|18|19)[0-9]{1}\d{8})$/; //手机号正则验证
      var mobile = this.userInfo.originalMobile;
      if (mobile == '') {

      } else if (!reg.test(mobile)) {
        return this.$toast("您输入的手机号码不合法，请重新输入");
      }
      // if (!reg.test(mobile)) { //手机号不合法
      // return  this.$toast("您输入的手机号码不合法，请重新输入");
      // }
      this.appFetch({
        url: 'users',
        method: 'patch',
        splice: `/${this.query.id}`,
        data: {
          "data": {
            "attributes": {
              'newPassword': this.newPassword,
              'mobile': mobile,
              'groupId': this.userRole,
              'status': this.userInfo.status,
              'refuse_message': this.reasonsForDisable,
            }
          }
        }
      }).then(res => {
        if (res.errors) {
          if (res.errors[0].detail) {
            this.$message.error(res.errors[0].code + '\n' + res.errors[0].detail[0])
          } else {
            this.$message.error(res.errors[0].code);
          }
        } else {
          this.$message({ message: '提交成功', type: 'success' });
        }
      })
    },

    async getUserList() {  //获取用户角色
      try {
        const response = await this.appFetch({
          method: 'get',
          url: 'groups'
        })
        const data = response.data;
        this.options = data.map((v) => {
          return {
            value: v.id,
            label: v.attributes.name
          }
        });
      } catch (err) {
        console.error(err, 'getUserList')
      }
    },
    userStatusChange(value) {
      this.disabledReason = value == 1;
      if (value != 1) {
        this.reasonsForDisable = '';
      }
    }

  },

  components: {
    Card,
    CardRow
  }
}
