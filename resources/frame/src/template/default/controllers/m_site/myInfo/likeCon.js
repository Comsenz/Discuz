/**
 * 点赞我的
 */

export default {
  data:function () {
    return {
      likeList:[],
      // imgUrl:'',
      // stateTitle:'点赞了我',
      // time:"5分钟前",
      // userName:'Elizabeth'
    }
  },
  mounted(){
    this.myLike()
  },
  methods:{
    myLike(){
      this.apiStore.find('notice',{type:2}).then(res=>{
        // console.log(res[0].user_id(), res[0].detail().post_content);
        this.likeList = res
      })
    }
  },
  created(){
    this.imgUrl = "../../../../../../../static/images/mytx.png"
  }
}
