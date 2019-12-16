/**
 * 我的收藏
 */

import CollectionHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import ContHeader from '../../../view/m_site/common/cont/contHeaderView';
import ContMain from '../../../view/m_site/common/cont/contMainView';
import ContFooter from '../../../view/m_site/common/cont/contFooterView';


export default {
  data:function () {
    return {
      // imgUrl:'',
      // stateTitle:'点赞了我',
      // time:"15分钟前",
      // userName:'Elizabeth',
      // contText:'我们来看一下程序员经常去的 14 个顶级开发者社区，如果你还不知道它们，那么赶紧去看看，也许会有意想不到的收获。',
      // aaa:[],
      collectionList:[
        
      ],
      list: [],
      loading: false,
      finished: false,
      isLoading: false,
    }
  },
  // components:{
  //   CollectionHeader,
  //   ContHeader,
  //   ContMain,
  //   ContFooter
  // },
  // mounted(){
  //   this.myCollection()
  // },
  created(){
    this.imgUrl = "../../../../../../../static/images/mytx.png"
    this.myCollection();
    // console.log(typeof this.aaa);
  },
  methods:{
    myCollection(){
      console.log('33333333333333333333333')
      this.appFetch({
        url:'collection',
        method:'get',
        data:{
          include:['user', 'firstPost', 'lastThreePosts', 'lastThreePosts.user', 'firstPost.likedUsers', 'rewardedUsers'],
        }
      }).then(data=>{
        console.log(data)
        this.collectionList = data.readdata;
      })
        // const params = {
        //   // 'filter[user]': this.userId
        // };
        // params.include = 'user,firstPost,lastThreePosts,lastThreePosts.user,firstPost.likedUsers,rewardedUsers';
        // this.apiStore.find('collection', params).then(data => {
        //   console.log(data[0].user());
        //   this.collectionList = data;
        // });
      },
      onLoad(){
        setTimeout(()=>{
      
          // for(let i = 0;i<10;i++){
          //   this.collectionList.push(this.collectionList.length + 1)
          // }
          // 加载状态结束
        this.loading = false;
            // 数据全部加载完成
            if (this.collectionList.length >= 40) {
              this.finished = true;
            }
        },200)
      },
      onRefresh(){
        setTimeout(()=>{
          this.$toast('刷新成功');
          this.isLoading = false;
        },200)
      }
    
  }
  
}
