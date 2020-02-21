/**
 * pc 端首页控制器
 */
import browserDb from '../../../../../../helpers/webDbHelper';
export default {
    data: function () {
      return {
        searchVal: '',
        userParams: {
          'filter[type]': '2',
          'page[limit]': 20,
          'page[number]': 1,
          'include': 'fromUser'
        },
        firstComeIn: true, // 是否首次进入页面
        searchUserList: [],
        searchThemeList: [],
        userLoadMoreStatus: false,
        userLoadMorePageChange: false,
        userLoading: false,
        themeLoading: false,
        timerSearch: null, // 延迟器
        searchMaxSum: 3,
      }
    },
  //用于数据初始化
  created: function () {
    this.handleSearchUser();
    let searchWord = '';
    if (this.$route.query && this.$route.query.searchWord) {
      searchWord = this.$route.query.searchWord
    }
    this.onSearch(searchWord);
  },
  methods: {
    onSearch(val) {
      clearTimeout(this.timerSearch);
      this.searchVal = val;
      // if(this.searchVal === ''){
      //   this.searchUserList = [];
      //   return;
      // }
      console.log(this.searchVal);
      this.timerSearch = setTimeout(()=>{

        this.firstComeIn = false;

        // 用户搜索
        this.userParams['filter[username]'] = this.searchVal;
        this.userParams['page[number]'] = 1;

        this.handleSearchUser(true);
      },200)
    },
    onCancel() {
      // this.$router.push({ path:'/'});
    },

    async handleSearchUser(initStatus = false){
      if(initStatus){
        this.searchUserList = [];
      }
      if(this.userLoading){
        return;
      }
      this.userLoading = true;
      try{
        const currentPageNum = this.userParams['page[number]'];
        await this.appFetch({
          url:'follow',
          methods:'get',
          data: this.userParams
        }).then(data=>{
          console.log(data,'dadadada');
          this.userLoadMoreStatus = data.readdata.length > this.searchMaxSum;
          // this.searchUserList = data.readdata.splice(0,3);
          this.searchUserList = data.readdata;
        }).catch(err=>{
          if(this.userLoadMorePageChange && this.userParams['page[number]'] > 1){
            this.userParams['page[number]'] = currentPageNum - 1;
          }
        })
      } finally {
        this.userLoadMorePageChange = false;
        this.userLoading = false;
        // this.userParams['page[limit]'] = 2;
      }
    },

    //管理关注操作
       followSwitch(intiFollowVal,personUserId,index) {
         browserDb.setSItem('beforeVisiting',this.$route.path);
         console.log('参数',typeof intiFollowVal,intiFollowVal);
         let attri = new Object();
         let methodType = '';
         if (intiFollowVal == '0') {
           // console.log('已关注，显示关注TA');
           attri.to_user_id = personUserId;
           methodType = 'post';
           // this.oldFollow = intiFollowVal;
         } else if(intiFollowVal == '1') {
           attri.to_user_id = personUserId;
           methodType = 'delete';
         }
         // console.log(attri,'33333333-----');
         this.followRequest(methodType,attri,intiFollowVal,index);
       },

       //关注，取消关注
       followRequest(methodType,attri,intiFollowVal,index){
        this.appFetch({
            url: 'follow',
            method: methodType,
            data: {
              "data": {
                "type": "user_follow",
                "attributes": attri
              },

            }
          }).then((res) => {
            // console.log(res,'987654','---------------',intiFollowVal);
            if (res.errors){
              this.$toast.fail(res.errors[0].code);
              throw new Error(res.error)
            } else {
              if(methodType == 'delete'){
                this.searchUserList[index]._data.is_mutual = '0';
              } else {
                console.log('post');
                this.searchUserList[index]._data.is_mutual = '1';
              }
            }
          })
       },


    //点击用户名称，跳转到用户主页
    jumpPerDet:function(id){
      this.$router.push({ path:'/home-page'+'/'+id});
    },

  },

  mounted: function () {

  },
  beforeRouteLeave(to, from, next) {
    next()
  }
}
