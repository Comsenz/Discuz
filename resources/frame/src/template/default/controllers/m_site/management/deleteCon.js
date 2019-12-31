/**
 * pc 端首页控制器
 */
import { Bus } from '../../../store/bus.js';
export default {
	// props: {
	//        title: { // 组件的标题
	//          type: String,
	//          default: () => {
	//            return '';
	//          }
	//        },
	//        headOpeShow: { // 组件是否显示返回按钮和菜单按钮
	//          headOpeShow:Boolean,
	//          default: () => {
	//            return 'fasle';
	//          }
	//        }
	//    },
	data: function () {
		return {
			result: [],
			showScreen: false,

			// themeList: [
			// {
			// 	postHead: '',
			// 	checked: false,
			// 	postName: '我的名称',
			// 	postTime: '11分钟前',
			// 	postCon: '标题标题',
			// 	fabulousList: ['aaaa','bbbbbbb','cc','ddddddddddd','eee','ffffffffffffffff'],
			// 	fabulousNum: '20',
			// 	rewardList: ['wert','dfggf','cc','retregvt','eee','hgfjhrthtrtrg','sdjgdsjfgsdgfdsfhdsjgfhdsjgfdsjgfdj'],
			// 	commentList: [
			// 		{
			// 			commentName:'我是第一个',
			// 			commentWo: '第一个评价的内容'
			// 		},
			// 		{
			// 			commentName:'我是第二个',
			// 			commentWo: '第二个评价的内容内容内容，内容内容内容内容内容内容内容内容内容，内容内'
			// 		},
			// 	],
			// 	replyList: [
			// 		{
			// 			replyName:'aaaaaa',
			// 			commentsName:'bbb',
			// 			replyWo: '第一个回复的内容'
			// 		},
			// 		{
			// 			replyName:'cc',
			// 			commentsName:'dddddd',
			// 			replyWo: '第二个回复的内容内容内容，内容内容内容内容内容内容内容内容内容，内容内'
			// 		}
			// 	],

			// },
			// 	{
			// 		postHead: '',
			// 		checked: false,
			// 		postName: '名字2222',
			// 		postTime: '1个小时前',
			// 		postCon: '这是内容内容，这是内容内容这是内容，这是内容内容这是内容内容这是内容内容这是内容内容这',
			// 		fabulousList: ['aaaa','bbbbbbfcffffb','cc','ddddddd','eee','ffffffff'],
			// 		fabulousNum: '14',
			// 		rewardList: ['adasda','dsddddfggf','cc','regvt','eee','trtrg','dsdsadasd','sfsadasdsadsdddssasasasadasdsadsasadddddd']
			// 	}
			// ],
			themeListCon: [],
			checked: null,
			pageIndex: 1,
			pageLimit: 20,
			loading: false,
			finished: false,
			offset: 100,
			isLoading: false,

		}
	},

	created() {
		this.deleteList();
	},
	mounted() {

		Bus.$emit('setHeader', ['标题标题3443453454ee', 'fasle', 'false']);
	},
	methods: {
		async deleteAllClick(value) {
		console.log(value)
			let data = [];
			for (let i = 0; i < value.length; i++) {
				data.push({
					"type": "threads",
					"id": value[i],
					"attributes": {
						"isDeleted": true,
					}
				});
			}

			await this.appFetch({
				url:'threadsBatch',
				method:'patch',
				data:{
					'data':data
				}
			}).then(res=>{
				if (res.errors){
					this.$toast.fail(res.errors[0].code);
					// throw new Error(res.error)
				  }else{
				this.pageIndex = 1;
				this.deleteList(true)
				  }
			})


		},

		deleteList(initStatus = false) {
			return this.appFetch({
				url:'threads',
				method:'get',
				data:{
					include:['user,firstPost,lastThreePosts,lastThreePosts.user,firstPost.likedUsers,rewardedUsers'],
					'filter[isDeleted]':'no',
					'filter[categoryId]':'',
					'page[number]': this.pageIndex,
					'page[limit]': this.pageLimit
				}
			}).then(res=>{
				if (res.errors){
					this.$toast.fail(res.errors[0].code);
					throw new Error(res.error)
				  }else{
				if(initStatus){
				this.themeListCon = []
				}
				console.log(res.readdata)
				this.themeListCon =this.themeListCon.concat(res.readdata);
				this.loading = false;
				this.finished = res.readdata.length < this.pageLimit;
			}
			}).catch((err)=>{
				if(this.loading && this.pageIndex !== 1){
				  this.pageIndex--;
				}
				this.loading = false;
			  })
			// console.log('1234');
			// const params = {'filter[isDeleted]':'no','filter[categoryId]':''};
			// params.include = 'user,firstPost,lastThreePosts,lastThreePosts.user,firstPost.likedUsers,rewardedUsers';
			// // params.filter['isDeleted'] = 'no';
			// this.apiStore.find('threads', params).then(data => {
			// 	// console.log(data[0].firstPost().id());
			// 	// console.log(data[0].user().username());
			// 	this.themeListCon = data;
			// });
		},

		checkAll: function (checkAll) {
			console.log(this.$refs);
			this.$refs.checkboxGroup.toggleAll(true);
			//   this.checked = !this.checked;

		},
		toggleAll() {
			this.$refs.checkboxGroup.toggleAll();
		},
		//全选
		// checkAll() {
		//       this.$refs.checkboxGroup.toggleAll(true);
		//     },
		//退出批量删除
		signOutDele() {

		},

		/**
		 * 给导航添加点击状态
		 */
		addClass: function (index, event) {
			this.current = index;

			//获取点击对象
			var el = event.currentTarget;
			// alert("当前对象的内容："+el.innerHTML);
		},
		//筛选
		bindScreen: function () {
			//是否显示筛选内容
			this.showScreen = !this.showScreen;
		},

		hideScreen() {
			//是否显示筛选内容
			this.showScreen = false;
		},
		onLoad(){
			console.log('onLoadonLoadonLoad')
			this.loading = true;
			this.pageIndex++;
			this.deleteList();
		  },
	  
		  onRefresh(){
			this.pageIndex = 1;
			this.deleteList(true).then((res)=>{
			  this.$toast('刷新成功');
			  this.finished = false;
			  this.isLoading = false;
			}).catch((err)=>{
			  this.$toast('刷新失败');
			  this.isLoading = false;
			})
		  },

		  headerBack(){
			console.log("回退");
			this.$router.go(-1)
		  }


	},

	// mounted: function () {

	// 	// this.getVote();
	// 	// window.addEventListener('scroll', this.handleTabFix, true);
	// },
	beforeRouteLeave(to, from, next) {
		// window.removeEventListener('scroll', this.handleTabFix, true)
		next()
	}
}
