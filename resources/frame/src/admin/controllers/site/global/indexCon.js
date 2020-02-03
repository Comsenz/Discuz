import { mapMutations,mapState } from 'vuex';

export default {
  data:function () {
    return {

    }
  },
  methods:{
    ...mapMutations({
      setNum: 'site/SET_NUM',
      setLoading:'site/SET_LOADING'
    }),

    login(){
      this.$router.push('/login/loginview')
    },
    buttonClick(){
      this.setNum({name:'张三'});
    }

  },
  computed:mapState({
    //直接映射state内的数据
    loginState: state => state.login.loginState,

    //映射函数可以处理一些数据返回，在别的地方this.直接调用
    countPlusLocalState(state){
      return state.login.loginState + 10
    }
  }),
  mounted:function () {
    console.log("加载后台首页");
  }
}
