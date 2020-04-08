/*
* 用户管理
* */

import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data: function () {
    return {
      options: [],
      username: '',
      userUID: '',
      userRole: [],
      checked: false,
      userPhone: '',
      userWeChat: 'no',
      userStatus: [],
      isReal: 'no', //是否实名认证
      disabled: true,//禁用表单上的游客
      optionsStatus: [
        {
          value: '',
          label: '全部'
        },
        {
          value: 'normal',
          label: '正常'
        },
        {
          value: 'ban',
          label: '禁用'
        },
        {
          value: 'mod',
          label: '审核'
        }
      ],
      value: ''
    }
  },

  created() {
    this.getUserList();
  },

  methods: {
    checkedStatus(str) {
      setTimeout(() => {
        if (str) {
          let gd = document.getElementsByClassName('index-main-con__main')[0];
          gd.scrollTo(0, gd.scrollHeight);
        }
      }, 300);
    },
    searchBtn() {
      let query = {
        username: this.username.trim(),
        userUID: this.userUID.trim(),
        userRole: this.userRole,
        userStatus: this.userStatus,
        userPhone: this.userPhone.trim(),
        userWeChat: this.userWeChat,
        isReal: this.isReal,

      };
      if (!this.checked) {
        this.userPhone = '';
        this.userWeChat = 'no';
        this.isReal = 'no';

        if (query.username + query.userUID + query.userRole + query.userStatus === '') {
          query = {};
        } else {
          delete query.userPhone;
          delete query.userWeChat;
          delete query.isReal;
        }
      }
      this.$router.push({ path: '/admin/user-search-list', query })
    },

    async getUserList() {
      try {
        const response = await this.appFetch({
          method: 'get',
          url: 'groups'
        });
        if (response.errors) {
          this.$message.error(response.errors[0].code);
        } else {
          const data = response.data;
          this.options = data.map((v) => {
            return {
              value: v.id,
              label: v.attributes.name
            }
          })
        }

      } catch (err) {
        console.error(err, 'getUserList')
      }
    },

  },

  components: {
    Card,
    CardRow
  }
}
