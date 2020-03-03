/**
 * 移动端站点管理页控制器
 */

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
      limitList:'',
      moreMemberShow:''

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
    //请求初始化数据
    loadSite(){
      const userId = browserDb.getLItem('tokenId');
      var load =this.appFetch({
        url: 'users',
        method: 'get',
        splice:'/'+userId,
        data: {
          include: 'groups',
        }
      }).then((res) => {
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          // throw new Error(res.error)
        }else{
        this.roleList = res.readdata.groups;
        this.groupId = res.readdata.groups[0]._data.id;
        if(res.readdata._data.joinedAt =='' || res.readdata._data.joinedAt == null){
          this.joinedAt = res.readdata._data.createdAt;
        } else {
          this.joinedAt = res.readdata._data.joinedAt;
        }
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
          if (res.errors){
            this.$toast.fail(res.errors[0].code);
            // throw new Error(res.error)
          }else{
          this.limitList = res.readdata;
          }
        });

      });

      this.appFetch({
        url: 'forum',
        method: 'get',
        data: {
          include: ['users'],
        }
      }).then((res) => {
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          // throw new Error(res.error)
        }else{
        this.siteInfo = res.readdata;
        this.moreMemberShow = res.readdata._data.other.can_view_user_list;
        if(res.readdata._data.set_site.site_author){
          this.username = res.readdata._data.set_site.site_author.username;
        }
      }
      });
   return load
    },

    //查看更多站点成员
    moreCilrcleMembers(){
      this.$router.push({path:'circle-members'});
    },
    //点击站点成员头像，跳转到用户主页
    membersJump(userId){
      this.$router.push({path:'/home-page/'+userId});
    },
    onRefresh(){
            this.loadSite().then((res)=>{
            
            this.$toast('刷新成功');
            this.isLoading =false;
            this.finished = false;
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
