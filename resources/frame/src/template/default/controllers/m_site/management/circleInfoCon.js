/**
 * 移动端站点管理页控制器
 */

import Forum from '../../../../../common/models/Forum';
import browserDb from '../../../../../helpers/webDbHelper';
export default {
	data: function() {
		return {
		  siteInfo: false,
		  username:'',
		  joinedAt:'',
      expiredAt:'',
      isLoading: false, //是否处于下拉刷新状态
		  roleList:[],
      groupId:'',
      limitList:''

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
      var userId = browserDb.getLItem('tokenId');
      this.appFetch({
        url: 'users',
        method: 'get',
        splice:'/'+userId,
        data: {
          include: 'groups',
        }
      }).then((res) => {
        console.log(res);
        this.roleList = res.readdata.groups;
        this.groupId = res.readdata.groups[0]._data.id;
        if(res.readdata._data.joinedAt =='' || res.readdata._data.joinedAt == null){
          this.joinedAt = res.readdata._data.createdAt;
        } else {
          this.joinedAt = res.readdata._data.joinedAt;
        }
        if(res.readdata._data.expiredAt =='' || res.readdata._data.expiredAt == null){
          this.expiredAt = res.readdata._data.expiredAt;
        } else {
          this.expiredAt = res.readdata._data.expiredAt;
        }

        //请求权限列表数据
        this.appFetch({
          url: 'groups',
          method: 'get',
          splice:'/'+this.groupId,
          data: {
            include: ['permission'],
          }
        }).then((res) => {
          console.log(res);
          this.limitList = res.readdata;
          console.log(res.readdata.permission[0]._data.permission);
          console.log(res.readdata.permission[1]._data.permission);
          console.log(res.readdata.permission[2]._data.permission);
          console.log(res.readdata.permission[3]._data.permission);
          console.log(res.readdata.permission[4]._data.permission);
          console.log(res.readdata.permission[5]._data.permission);
          console.log(res.readdata.permission[6]._data.permission);
          console.log(res.readdata.permission[7]._data.permission);
          console.log(res.readdata.permission[8]._data.permission);
          console.log(res.readdata.permission[9]._data.permission);
          console.log(res.readdata.permission[10]._data.permission);
          console.log(res.readdata.permission[11]._data.permission);
          console.log(res.readdata.permission[12]._data.permission);
          console.log(res.readdata.permission[13]._data.permission);
        });

      });

      this.appFetch({
        url: 'forum',
        method: 'get',
        data: {
          include: ['users'],
        }
      }).then((res) => {
        console.log(res);
        this.siteInfo = res.readdata;
        console.log(this.siteInfo._data.logo);
        if(res.readdata._data.siteAuthor){
          this.username = res.readdata._data.siteAuthor.username;
        }
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
          this.loadSite().then((res)=>{
            this.$toast('刷新成功');
            this.isLoading = false;
            this.finished = true;
          }).catch((err)=>{
            this.$toast('刷新失败');
            this.isLoading = false;
          })
    }
	},

	mounted: function() {

	},
	beforeRouteLeave (to, from, next) {
    next();
	}
}
