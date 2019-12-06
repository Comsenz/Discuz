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
			checked: null

		}
	},

	created() {
		this.deleteList();
	},
	mounted() {

		Bus.$emit('setHeader', ['标题标题3443453454ee', 'fasle', 'false']);
	},
	methods: {

		deleteAllClick(value) {
			console.log(value);
			this.appFetch({
				url:'batch',
				method:'patch',
				data:{
				'data':[
					{
						"type": "threads",
						"id": value,
						"attributes": {
							"isDeleted": true,
						},
					}
				]
				}
			})

		},

		deleteList() {
			console.log('1234');
			const params = {};
			params.include = 'user,firstPost,lastThreePosts,lastThreePosts.user,firstPost.likedUsers,rewardedUsers';
			this.apiStore.find('threads', params).then(data => {
				// console.log(data[0].firstPost().id());
				// console.log(data[0].user().username());
				this.themeListCon = data;
			});
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
