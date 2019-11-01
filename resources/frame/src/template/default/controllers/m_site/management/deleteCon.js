/**
 * pc 端首页控制器
 */

export default {
	data: function() {
		return {
			showScreen: false,
			checked: false, //全选
			themeList: [
				{
					postHead: '',
					postName: '我的名称',
					postTime: '11分钟前',
					postCon: '标题标题',
					fabulousList: ['aaaa','bbbbbbb','cc','ddddddddddd','eee','ffffffffffffffff'],
					fabulousNum: '20',
					rewardList: ['wert','dfggf','cc','retregvt','eee','hgfjhrthtrtrg','sdjgdsjfgsdgfdsfhdsjgfhdsjgfdsjgfdj'],
					commentList: [
						{
							commentName:'我是第一个',
							commentWo: '第一个评价的内容'
						},
						{
							commentName:'我是第二个',
							commentWo: '第二个评价的内容内容内容，内容内容内容内容内容内容内容内容内容，内容内'
						},
					],
					replyList: [
						{
							replyName:'aaaaaa',
							commentsName:'bbb',
							replyWo: '第一个回复的内容'
						},
						{
							replyName:'cc',
							commentsName:'dddddd',
							replyWo: '第二个回复的内容内容内容，内容内容内容内容内容内容内容内容内容，内容内'
						},
					],
					checked: false
				},
				{
					postHead: '',
					postName: '名字2222',
					postTime: '1个小时前',
					postCon: '这是内容内容，这是内容内容这是内容，这是内容内容这是内容内容这是内容内容这是内容内容这',
					fabulousList: ['aaaa','bbbbbbfcffffb','cc','ddddddd','eee','ffffffff'],
					fabulousNum: '14',
					rewardList: ['adasda','dsddddfggf','cc','regvt','eee','trtrg','dsdsadasd','sfsadasdsadsdddssasasasadasdsadsasadddddd'],
					checked: false
				}
			]
			
		}
	},
	
	created() {
	    
	},
	methods: {
		//全选
		checkAll() {
			// console.log('sssss');
			let a = !this.checked;
		    this.themeList = this.themeList.map(e => {
		        e.checked = a;
		        return e;
		    });
	      // this.$refs.checkboxGroup.toggleAll(true);
	    },
	    //退出批量删除
	    signOutDele(){

	    },
	    
		/**
		 * 给导航添加点击状态
		 */
		addClass:function(index,event){
	    this.current=index;
     
　　　　　　 //获取点击对象      
		var el = event.currentTarget;
		   // alert("当前对象的内容："+el.innerHTML);
		},
	    //筛选
	    bindScreen:function(){
	        //是否显示筛选内容
	        this.showScreen = !this.showScreen;
	    },
	      
	    hideScreen(){
	        //是否显示筛选内容
	        this.showScreen = false;
	    },
		
	},

	mounted: function() {
		// this.getVote();
		// window.addEventListener('scroll', this.handleTabFix, true);
	},
	beforeRouteLeave (to, from, next) {
	   // window.removeEventListener('scroll', this.handleTabFix, true)
	   // next()
	}
}