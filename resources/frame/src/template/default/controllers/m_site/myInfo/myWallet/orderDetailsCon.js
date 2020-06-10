import orderDetailsHeader from "../../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader";
import Panel from "../../../../view/m_site/common/panel";
import webDb from "../../../../../../helpers/webDbHelper";

export default {
  data: function() {
    return {
      orderList: [],
      type: {
        1: "加入圈子",
        2: "打赏帖子",
        3: "付费查看帖子"
      },
      status: {
        0: "待付款",
        1: "已付款"
      },
      pageIndex: 1,
      pageLimit: 20,
      loading: false,
      finished: false,
      offset: 100,
      isLoading: false,
      userId: "",
      concent: ""
    };
  },

  components: {
    orderDetailsHeader,
    Panel
  },
  created() {
    this.userId = webDb.getLItem("tokenId");
    this.order();
  },
  methods: {
    order(initStatus = false) {
      return this.appFetch({
        url: "orderList",
        method: "get",
        data: {
          include: "thread.firstPost",
          "filter[user]": this.userId,
          "page[number]": this.pageIndex,
          "page[limit]": this.pageLimit
        }
      })
        .then(res => {
          if (res.errors) {
            this.$toast.fail(res.errors[0].code);
            throw new Error(res.error);
          } else {
            if (initStatus) {
              this.orderList = [];
            }
            res.readdata.map(item => {
              switch (item._data.type) {
                case 1:
                  item.title = this.type[item._data.type];
                  break;
                case 2:
                case 3:
                  var orderThread = item.thread ? item.thread._data : null;
                  item.title = this.type[item._data.type];
                  item.title += orderThread
                    ? `<a href='details/${orderThread.id}'>“${orderThread.title}”</a>`
                    : "“该主题被删除”";
                  break;
                default:
                  item.title = "unknown order type";
              }
            });
            this.orderList = this.orderList.concat(res.readdata);
            this.loading = false;
            this.finished = res.readdata.length < this.pageLimit;
          }
        })
        .catch(err => {
          if (this.loading && this.pageIndex !== 1) {
            this.pageIndex--;
          }
          this.loading = false;
        });
    },

    //点击主题内容，跳转到详情页
    jumpDetails: function(id) {
      this.$router.push({ path: "/details" + "/" + id });
    },

    onLoad() {
      this.loading = true;
      this.pageIndex++;
      this.order();
    },

    onRefresh() {
      this.pageIndex = 1;
      this.order(true)
        .then(res => {
          this.$toast("刷新成功");
          this.finished = false;
          this.isLoading = false;
        })
        .catch(err => {
          this.$toast("刷新失败");
          this.isLoading = false;
        });
    }
  }
};
