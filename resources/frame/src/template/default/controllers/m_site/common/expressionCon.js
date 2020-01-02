/**
 * 移动端发帖表情组件控制器
 */
export default {
  data: function() {
      return {
		 active: 0,
		 faceIndex: 0

    }
  },
  props: {
    faceData:{
      type:Array

    }
  },
  computed: {
    faces: function () {
      const { faceData, faceIndex } = this
      const list = faceData;
      // console.log(list);
      let n = 0
      let listGrouped = []
      while (n * 28 < list.length) {
        listGrouped.push(list.slice(n * 28, (n + 1) * 28))
        n += 1
      }
      return listGrouped
    },

    scrollWidth: function () {
      // return this.faces.length * document.body.scrollWidth;
      return this.faces.length * document.body.clientWidth;
    },
    scrollPosition: function () {
      // return this.active * document.body.scrollWidth
      return this.active * document.body.clientWidth
    }
  },
  mounted () {
    const el = this.$refs.faceContent
    let x1 = 0
    let x2 = 0
    el.ontouchstart = (evt) => {
      x1 = evt.targetTouches[0].pageX
    }
    el.ontouchend = (evt) => {
      x2 = evt.changedTouches[0].pageX
      if (x2 - x1 > 50) {
        this.active !== 0 && this.active--
      } else if (x2 - x1 < -50) {
        this.active !== this.faces.length - 1 && this.active++
      }
    }
  },
	created(){

    // this.getUrlCode();
  },
  methods:{

    //点击“微信授权”
    getUrlCode(){
      this.code = this.$utils.getUrlKey('code');
      alert(code);
      this.appFetch({
        url:"weixin",
        method:"get",
        data:{
          code:this.code,
        }
      }).then(res =>{
        alert(65756765);
        // console.log(res, '111');
        // window.location.href = res.data.attributes.location;
      }, error => {
        if(error.errors[0].status == 100004){
          // this.$router.push({
          //   path:'circle',
          // });
          this.$router.go(-1);
        }
      })
    },
    loginWxClick(){
      this.$router.push({path:'/wx-login-bd'})
    },
    loginPhoneClick(){
      this.$router.push({path:'/login-phone'})
    },
    // onTypeClick (index) {
    //   this.faceIndex = index
    //   this.active = 0
    // },
    onFaceClick (face) {
      // console.log(face);
      this.$emit('onFaceChoose', face);
    }
  }

}
