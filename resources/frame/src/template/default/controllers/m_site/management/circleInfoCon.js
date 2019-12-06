/**
 * 移动端圈子管理页控制器
 */

import Forum from '../../../../../common/models/Forum';
import browserDb from '../../../../../helpers/webDbHelper';
export default {
	data: function() {
		return {
		  siteInfo: new Forum(),
		  username:''
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
    //请求初始化圈子信息数据
    loadSite(){
      const params = {};
       params.include='users';
       this.apiStore.find('forum',params).then(data => {
         console.log(data.user());
         this.siteInfo = data;
         this.username = data.siteAuthor().username;
         // console.log(data.user().avatarUrl());
      });
    },
    //查看更多圈子成员
    moreCilrcleMembers(){
      this.$router.push({path:'circle-members'});
    }
	},

	mounted: function() {

	},
	beforeRouteLeave (to, from, next) {

	}
}
