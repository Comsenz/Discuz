/**
 * 移动端站点管理页控制器
 */

export default {
  data: function () {
    return {
      // serHide: true,
      serShow: true,
      searchVal: '',
      inputSearchVal:'',
      searchThemeList: [],
      themeLoadMorePageChange: false,
      loading: false, //是否处于加载状态
      finished: false, //是否已加载完所有数据
      isLoading: false, //是否处于下拉刷新状态
      pageIndex: 0, //页码
      offset: 100, //滚动条与底部距离小于 offset 时触发load事件
      immediateCheck: false, //是否在初始化时立即执行滚动位置检查
      pageLimit: 20,
      searchTimer: null,
      // placeholder:'', //搜索框回填
    }
  },
  //用于数据初始化
  created: function () {
    // this.loadUserList();
    // let searchWord = '';
    if (this.$route.query && this.$route.query.searchWord) {
      this.searchVal = this.$route.query.searchWord;
      if(this.searchVal){
        // this.serShow = true;
        // this.serShow = false;
        this.onSearch(this.searchVal);
      }else{
      
      }
      // this.inputSearchVal =  this.$route.query.searchWord;
    }
    this.onSearch(this.searchVal); 
  },
  methods: {
    //搜索框切换
    serToggle() {
      this.serHide = false;
      this.serShow = true;
      this.$refs.serInp.focus();
    },
    onSearch(val) {
      clearTimeout(this.searchTimer);
      this.searchVal = val;
      this.pageIndex = 0;
      this.searchTimer = setTimeout(()=>{
          this.handleSearchUser(true);
      }, 220)
    },
    onCancel() {},
    async handleSearchUser(initStatus = false) {
      try {
        await this.appFetch({
            url: 'searchThreads',
            method: 'get',
            //   data:this.userParams
            data: {
              include: ['user', 'firstPost', 'firstPost.images', 'lastThreePosts', 'lastThreePosts.user', 'lastThreePosts.replyUser', 'firstPost.likedUsers', 'rewardedUsers', 'threadVideo'],
              'filter[q]': this.searchVal.trim(),
              'page[number]': this.pageIndex,
              'page[limit]': this.pageLimit
            }
          }).then(data => {
            if (data.errors) {
              this.$toast.fail(data.errors[0].code);
              throw new Error(data.error)
            } else {
              if (initStatus) {
                this.searchThemeList = [];
              }
              this.loading = false;
              this.searchThemeList = this.searchThemeList.concat(data.readdata);
              this.finished = data.readdata.length < this.pageLimit;
            }
          })
          .catch(err => {
            if (this.loading && this.pageIndex !== 1) {
              this.pageIndex--;
            }
          })
      } finally {
        this.themeLoadMorePageChange = false;
        this.loading = false;
      }
    },

    handleLoadMoreUser() {
      this.themeLoadMorePageChange = true;
      this.handleSearchUser();
    },
    onLoad() { //上拉加载
      this.loading = true;
      this.pageIndex++;
      this.handleSearchUser()
    },
    onRefresh() {
      this.pageIndex = 1
      this.handleSearchUser(true).then(() => {
        this.$toast('刷新成功');
        this.isLoading = false;
        this.finished = false;
      }).catch((err) => {
        this.$toast('刷新失败');
        this.isLoading = false;
      })
    },
    headerBack() {
      this.$router.go(-1)
    },
    //点击用户名称，跳转到用户主页
    jumpPerDet: function (id) {
      //跳转到个人主页
      this.$router.push({
        path: '/home-page' + '/' + id
      });
    },
  },

  mounted() {

  },
  beforeRouteLeave(to, from, next) {
    next();
  }
}
