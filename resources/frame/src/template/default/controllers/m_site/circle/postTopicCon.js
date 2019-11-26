/**
 * 发布主题控制器
 */

export default {
  data:function () {
    return {
      headerTitle:"发布主题",
      selectSort:'选择分类',
      showPopup:false,
      columns: ['杭州', '宁波', '温州', '嘉兴', '湖州'],
      content:'',
      keyboard: false,
      expressionShow: false,
      // winHeight:window.innerHeight,
      images_jinri: [
              {url:'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1574602323031&di=3929e3b520f6481e305657c9974531c4&imgtype=0&src=http%3A%2F%2Fpics5.baidu.com%2Ffeed%2F5366d0160924ab189edfd7000e457ec87a890b7b.jpeg%3Ftoken%3D56b0caae9d228ba8aca8b93aceb5a658%26s%3D12705285C45AA7DC7CC9F5860300F085'},
              {url:'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1574602323048&di=80e49646b75febfa1d1c17dc6fbea2b8&imgtype=0&src=http%3A%2F%2Fwx3.sinaimg.cn%2Fbmiddle%2F005Ll667ly1g8gcdaeo4sj30k00f00tg.jpg'},
              {url:'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1574602323048&di=48329d6ab9abeda00f382fb074dbac8b&imgtype=0&src=http%3A%2F%2Fb-ssl.duitang.com%2Fuploads%2Fitem%2F201806%2F19%2F20180619083354_totbw.jpg'}
            ],
      fileList: [
        { url: 'https://img.yzcdn.cn/vant/leaf.jpg' },
        // Uploader 根据文件后缀来判断是否为图片文件
        // 如果图片 URL 中不包含类型信息，可以添加 isImage 标记来声明
        { url: 'https://cloud-image', isImage: true }
      ]
    }
  },

  // mounted(){
  // //挂载浏览器高度获取方法
  //   const that =this
  //   window.onresize = () => {
  //     return (() => {
  //       that.winHeight = window.innerHeight
  //       console.log(that.winHeight)

  //     })()
  //   }
  //   // this.$refs.nav.style.height=this.winHeight+'px'
  // },
  // watch:{
  //   //监控浏览器高度变化
  //   winHeight(val){
  //     this.winHeight =val;
  //     console.log(this.winHeight)
  //     // this.$refs.nav.style.height=this.winHeight+'px'
  //   }
  // },
  // created(){
  //   console.log(this.winHeight)
  // },


  methods: {

    publish(){
      this.appFetch({
        url:"threads",
        method:"post",
        data:{
          content:this.content,
        },
      },(res)=>{
        alert('234');
        console.log(res);
        if (res.status === 200){
          console.log(res);
        } else{
          console.log('400');
        }

      },(err)=>{
        alert('45656');
        // console.log(err);
      })
    },
    addExpression(){
      this.keyboard = !this.keyboard;
      this.expressionShow = !this.expressionShow;
    },
    backClick() {
      this.$router.go(-1);
    },
    dClick() {
      this.showPopup = true;
    },
    onConfirm(value, index) {
      this.showPopup = false;
      this.selectSort = value;
      // Toast(`当前值：${value}, 当前索引：${index}`);
    },
    onCancel() {
      this.showPopup = false;
    }

  }
}
