/**
 * 移动端站点管理页控制器
 */

import Forum from '../../../../../common/models/Forum';
import browserDb from '../../../../../helpers/webDbHelper';
export default {
	data: function() {
		return {
		  siteInfo: new Forum(),
      username:'',
      isLoading: false, //是否处于下拉刷新状态
		}
	},
  beforeCreate:function(){
  },
	 //用于数据初始化
  created: function(){
    this.loadSite();
    var userId = browserDb.getLItem('tokenId');

	},
  beforeMount(){

  },
	methods: {
    //请求初始化站点信息数据
    loadSite(){
      const params = {};
       params.include='users';
       this.apiStore.find('forum',params).then(data => {
         // console.log(data.users());
         this.siteInfo = data;
         this.username = data.siteAuthor().username;
         // console.log(data.user().avatarUrl());
      });
    },
    //查看更多站点成员
    moreCilrcleMembers(){
      this.$router.push({path:'circle-members'});
    },
    //点击站点成员头像，跳转到用户主页
    membersJump(userId){
      console.log('2222');
      this.$router.push({path:'/home-page/'+userId});
    },
    onRefresh(){
      setTimeout(()=>{
          this.loadSite()
          this.$toast('刷新成功');
          this.isLoading = false;
          this.finished = true; 
      },200)
    }
	},

	mounted: function() {

	},
	beforeRouteLeave (to, from, next) {
    next();
	}
}
