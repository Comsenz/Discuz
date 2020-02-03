/**
 * 移动端站点管理页控制器
 */

export default {
	data: function() {
		return {
			canBatchEditThreads:false,
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
          this.canBatchEditThreads = res.readdata._data.other.can_batch_edit_threads;
          this.canEditUserGroup = res.readdata._data.other.can_edit_user_group;
          this.canCreateInvite = res.readdata._data.other.can_create_invite;
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
