/**
 * 移动端站点管理页控制器
 */

export default {
	data: function() {
		return {
			canHideThreads:false,
			canEditUserGroup:false,
			canCreateInvite:false
		}
	},
	 //用于数据初始化
    created: function(){
		// console.log(this.headOneShow)
		this.managementCircles();
    this.getInfo();
	},
	methods: {
	    //跳转到成员管理
	    // loginJump:function(){
	    // 	// alert('跳转到成员管理');
	    // 	this.$router.push({ path:'/open-circle'});
	    // 	// console.log(this.$router);
	    // },
	    // //跳转到批量管理
	    // registerJump:function(){
	    // 	// alert('跳转到批量管理');
	    // 	this.$router.push({ path:'/sign-up'});
	    // },
		// //跳转到批量管理
	    // postTopic:function(){
	    // 	// alert('跳转到邀请成员');
	    // 	this.$router.push({ path:'/post-topic'});
	    // }

	// },
    managementCircles(str){
      switch (str) {
        case 'members-management':
        this.$router.push('/members-management'); //成员管理
        break;
        case 'delete':
        this.$router.push('/delete'); //批量管理
        break;
        case 'invite-join':
        this.$router.push('/invite-join'); //成员邀请
        break;
        default:
        // this.$router.push('/');
      }
	  },
    getInfo() {
        //请求站点信息，用于判断站点是否是付费站点
        this.appFetch({
          url: 'forum',
          method: 'get',
          data: {
          }
        }).then((res) => {
          if (res.errors){
              this.$toast.fail(res.errors[0].code);
              throw new Error(res.error)
            } else {
             // console.log(res);
             this.canHideThreads = res.readdata._data.canHideThreads;
             this.canEditUserGroup = res.readdata._data.canEditUserGroup;
             this.canCreateInvite = res.readdata._data.canCreateInvite;
             // console.log(this.canHideThreads,this.canEditUserGroup,this.canCreateInvite);
             //判断 当用户组拥有批量删除帖子、管理-邀请加入、编辑用户组、编辑用户组状态这4个权限中的任意一项时才会显示该菜单
             if(this.canHideThreads || this.canEditUserGroup || this.canCreateInvite){
               // this.sidebarList2.splice(1,1);
             }
           }
        });
      },
	},


	mounted: function() {

	},
	beforeRouteLeave (to, from, next) {
	   next()
	}
}
