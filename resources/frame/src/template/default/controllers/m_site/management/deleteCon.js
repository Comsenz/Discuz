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
			loading: false,  //是否处于加载状态
			finished: false, //是否已加载完所有数据
			isLoading: false, //是否处于下拉刷新状态
			// pageSize:'',//每页的条数
			pageIndex: 1,//页码
			offset: 100, //滚动条与底部距离小于 offset 时触发load事件

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
			})
			this.deleteList()

		},

		deleteList() {
			return this.appFetch({
				url:'threads',
				method:'get',
				data:{
					include:['user,firstPost,lastThreePosts,lastThreePosts.user,firstPost.likedUsers,rewardedUsers'],
					'filter[isDeleted]':'no',
					'filter[categoryId]':'',
					'page[number]': this.pageIndex,
					'page[limit]': 1
				}
			}).then(res=>{
				console.log(res.readdata)
				this.themeListCon = res.readdata;
				this.pageIndex++;
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
		onLoad(){    //上拉加载
			this.appFetch({
			  url:'threads',
			  method:'get',
			  data:{
				include:['user', 'firstPost', 'lastThreePosts', 'lastThreePosts.user', 'firstPost.likedUsers', 'rewardedUsers'],
				'filter[isDeleted]':'no',
				'filter[categoryId]':'',
				'page[number]': this.pageIndex,
				'page[limit]': 5
			  }
			}).then(res=>{
			  console.log(res.readdata)
			  this.loading = false;
			  if(res.readdata.length > 0){
				this.themeListCon = this.themeListCon.concat(res.readdata);
				this.pageIndex++;
				this.finished = false; //数据全部加载完成
			  }else{
				this.finished = true
			  }
			})
		  },
		  onRefresh(){    //下拉刷新
			setTimeout(()=>{
			  this.pageIndex = 1;
			  this.deleteList().then(()=>{
				this.$toast('刷新成功');
				this.isLoading = false;
				this.finished = false;
			  })
			  
			},200)
		  }

	},

	mounted: function () {
		// this.getVote();
		// window.addEventListener('scroll', this.handleTabFix, true);
	},
	beforeRouteLeave(to, from, next) {
		// window.removeEventListener('scroll', this.handleTabFix, true)
		next()
	}
}
