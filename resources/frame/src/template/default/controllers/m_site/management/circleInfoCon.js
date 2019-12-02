/**
 * 移动端圈子管理页控制器
 */

import Forum from '../../../../../common/models/Forum';

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

	},
  beforeMount(){

  },
	methods: {
    loadSite(){
      const params = {};
       params.include='users';
       this.apiStore.find('forum').then(data => {
         this.siteInfo = data;
         this.username = data.siteAuthor().username
      });
    }
	},

	mounted: function() {

	},
	beforeRouteLeave (to, from, next) {

	}
}
