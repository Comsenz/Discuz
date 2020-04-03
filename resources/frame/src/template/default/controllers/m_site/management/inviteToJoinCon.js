/**
 * 移动端站点管理页控制器
 */
import myInviteJoinHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import appConfig from "../../../../../../../frame/config/appConfig";
import appCommonH from '../../../../../helpers/commonHelper';
export default {
  data: function () {
    return {
      inviteList: [],
      choiceShow: false,
      checkOperaStatus: false,
      choList: [],
      getGroupNameById: {},
      choiceRes: {
        attributes: {
          name: '选择操作'
        }
      },
      loading: false, //是否处于加载状态
      finished: false, //是否已加载完所有数据
      isLoading: false, //是否处于下拉刷新状态
      // pageSize:'',//每页的条数
      pageIndex: 1, //页码
      offset: 100, //滚动条与底部距离小于 offset 时触发load事件
      query: {},
      pageLimit: 15,
      isWeixin: false,
      isPhone: false,
      viewportWidth: '',
    }
  },
  components: {
    myInviteJoinHeader
  },
  //用于数据初始化
  created: async function () {
    this.viewportWidth = window.innerWidth;
    this.isWeixin = appCommonH.isWeixin().isWeixin;
    this.isPhone = appCommonH.isWeixin().isPhone;
    await this.getOperaType();
    this.query = this.$route.query;
    this.getInviteList();
  },
  methods: {
    //选中复选框
    toggle(index) {
      this.$refs.checkboxes[index].toggle();
    },
    //操作列表显示
    showChoice() {
      this.choiceShow = !this.choiceShow;
    },
    //操作列表隐藏
    setSelectVal: function (val) {
      this.choiceShow = false;
      this.checkOperaStatus = true;
      this.choiceRes = val;
    },

    // 获取操作类型
    async getOperaType() {
      try {
        const response = await this.appFetch({
          url: 'groups',
          splice: '?type=invite',
          method: 'get'
        })
        if (response.errors) {
          this.$toast.fail(response.errors[0].code);
          throw new Error(response.error)
        } else {
          this.choList = response.data;
          for (let val of this.choList) {
            this.getGroupNameById[val.id] = val.attributes.name;
          }
        }
      } catch (err) {
        this.$toast("邀请码类型获取失败，请刷新重试");
      }
    },

    // 获取邀请码列表
    async getInviteList(initStatus = false) {
      try {
        await this.appFetch({
          url: 'invite',
          method: 'get',
          data: {
            // data: {
            //   type: "invite",
            //   attributes: {
            //     group_id: parseInt(this.choiceRes.id)
            //   }
            // },
            'page[number]': this.pageIndex,
            'page[limit]': this.pageLimit
          }
        }).then(res => {
          if (res.errors) {
            this.$toast.fail(res.errors[0].code);
            throw new Error(res.error)
          } else {
            this.finished = res.readdata.length < this.pageLimit; //数据全部加载完成
            if (initStatus) {
              this.inviteList = [];
            }
            this.loading = false;
            this.inviteList = this.inviteList.concat(res.readdata);

          }
        })
      } catch (err) {
        this.$toast("邀请列表获取失败");
        if (this.loading && this.pageIndex !== 1) {
          this.pageIndex--;
        }
      }
    },

    // 生成邀请码点击事件
    async checkSubmit() {
      if (!this.checkOperaStatus) {
        // 提示用户选择邀请码类型
        return;
      }
      try {
        await this.appFetch({
          url: 'invite',
          method: 'post',
          data: {
            data: {
              type: "invite",
              attributes: {
                group_id: parseInt(this.choiceRes.id)
              },

            }
          }
        })
        // if (res.errors){
        //   this.$toast.fail(res.errors[0].code);
        //   throw new Error(res.error)
        // }else{
        this.pageIndex = 1;
        // this.finished = false;
        this.getInviteList(true)
        // }
      } catch (err) {
        console.error(err, 'checkSubmit')
      }
    },

    copyToClipBoard(inviteItem) { //复制
      if (inviteItem._data.status === 0) {
        return;
      }
      // console.log(inviteItem._data.code,'1223444');
      var textarea = document.createElement('textarea');
      textarea.style.position = 'absolute';
      textarea.style.opacity = '0';
      textarea.style.height = '0';
      textarea.textContent = `${appConfig.baseUrl}/circle-manage-invite?code=${inviteItem._data.code}`;
      this.$toast.success('邀请链接已复制成功');
      document.body.appendChild(textarea);
      textarea.select(textarea, '链接链接');
      try {
        return document.execCommand('copy');
      } finally {
        document.body.removeChild(textarea);
      }
    },

    // 置为无效的点击事件
    async resetDelete(inviteItem, index) {
      // if (inviteItem._data.status != 1) {
      //   return;
      // }
      const id = inviteItem._data.id;
      try {
        const res = await this.appFetch({
          url: 'invite',
          method: 'delete',
          splice: `/${id}`
        })
        if (res.errors) {
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        } else {
          // this.getInviteList();
          this.inviteList[index]._data.status = 0;
        }
      }
      catch (err) {
        console.log(err)
        this.$toast("邀请码操作失败！");
      }

    },
    onLoad() { //上拉加载
      this.loading = true;
      this.pageIndex++;
      this.getInviteList();
    },
    onRefresh() { //下拉刷新

      this.pageIndex = 1;
      this.getInviteList(true).then(res => {
        this.$toast('刷新成功');
        this.isLoading = false;
        this.finished = false;
      }).catch((err) => {
        this.$toast('刷新失败');
        this.isLoading = false;
      });

    }
  },

  mounted: function () {

  },
  beforeRouteLeave(to, from, next) {
    next()
  }
}
