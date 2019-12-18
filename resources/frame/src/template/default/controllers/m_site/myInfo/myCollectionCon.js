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
      loading: false,  //是否处于加载状态
      finished: false, //是否已加载完所有数据
      isLoading: false, //是否处于下拉刷新状态
      // pageSize:'',//每页的条数
      pageIndex: 1,//页码
      offset: 100
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
      return this.appFetch({
        url:'collection',
        method:'get',
        data:{
          include:['user', 'firstPost', 'lastThreePosts', 'lastThreePosts.user', 'firstPost.likedUsers', 'rewardedUsers'],
          'page[number]': this.pageIndex,
          'page[limit]': 1
        }
      }).then(data=>{
        this.collectionList = data.readdata;
        this.pageIndex++;
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
      onLoad(){    //上拉加载
        this.appFetch({
          url:'collection',
          method:'get',
          data:{
            include:['user', 'firstPost', 'lastThreePosts', 'lastThreePosts.user', 'firstPost.likedUsers', 'rewardedUsers'],
            'page[number]': this.pageIndex,
            'page[limit]': 1
          }
        }).then(res=>{
          console.log(res.readdata)
          this.loading = false;
          if(res.readdata.length > 0){
            // console.log(this.collectionList)
            this.collectionList = this.collectionList.concat(res.readdata);
            // console.log(this.collectionList)
            this.pageIndex++;
            this.finished = false; //数据全部加载完成
          }else{
            this.finished = true
          }
        })
        // setTimeout(()=>{
          
        // this.loading = false;
        //     // 数据全部加载完成
        //     if (this.collectionList.length >= 40) {
        //       this.finished = true;
        //     }
        // },200)
      },
      onRefresh(){
        setTimeout(()=>{
          this.myCollection().then(()=>{
            this.$toast('刷新成功');
            this.isLoading = false;
            this.finished = true;
          })
          
        },200)
      }
    
  }
  
}
