/**
 * 发布主题控制器
 */
import { debounce, autoTextarea } from '../../../../../common/textarea.js';
import Store from '../../../../../common/Store.js';
import Post from '../../../../../common/models/Post.js';

let rootFontSize = parseFloat(document.documentElement.style.fontSize);
export default {
  data:function () {
    return {
      headerTitle:"发布主题",
      selectSort:'选择分类',
      showPopup:false,
      columns: ['杭州', '宁波', '温州', '嘉兴', '湖州'],
      content:'',
      showFacePanel: false,
      keyboard: false,
      // expressionShow: false,
      keywordsMax: 1000,
      list: [],
      footMove: false,
      faceData:[],
      // winHeight:window.innerHeight,
      // images_jinri: [
      //         {url:'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1574602323031&di=3929e3b520f6481e305657c9974531c4&imgtype=0&src=http%3A%2F%2Fpics5.baidu.com%2Ffeed%2F5366d0160924ab189edfd7000e457ec87a890b7b.jpeg%3Ftoken%3D56b0caae9d228ba8aca8b93aceb5a658%26s%3D12705285C45AA7DC7CC9F5860300F085'},
      //         {url:'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1574602323048&di=80e49646b75febfa1d1c17dc6fbea2b8&imgtype=0&src=http%3A%2F%2Fwx3.sinaimg.cn%2Fbmiddle%2F005Ll667ly1g8gcdaeo4sj30k00f00tg.jpg'},
      //         {url:'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1574602323048&di=48329d6ab9abeda00f382fb074dbac8b&imgtype=0&src=http%3A%2F%2Fb-ssl.duitang.com%2Fuploads%2Fitem%2F201806%2F19%2F20180619083354_totbw.jpg'}
      //       ],
      fileList: [
        { url: 'https://img.yzcdn.cn/vant/leaf.jpg' }
        // Uploader 根据文件后缀来判断是否为图片文件
        // 如果图片 URL 中不包含类型信息，可以添加 isImage 标记来声明
        // { url: 'https://cloud-image', isImage: true }
      ],

      themeId:'',
      postsId:''
    }
  },

  mounted () {
      this.$nextTick(() => {
        let textarea = this.$refs.textarea;
        textarea.focus();
        let prevHeight = 300;
        textarea && autoTextarea(textarea, 5, 0, (height) => {
          height += 20;
          if (height !== prevHeight) {
            prevHeight = height;
            let rem = height / rootFontSize;
            // this.$refs.list.style.height = `calc(100% - ${rem}rem)`;
          }
        });
      })
  },
  created(){
    var themeId = this.$route.params.themeId;
    var postsId = this.$route.params.postsId;
    var themeContent = this.$route.params.themeContent;
    console.log(themeId)
    this.themeId = themeId;
    this.postsId = postsId;
    this.content = themeContent;
  },

  methods: {
    publish(){
      if(this.postsId && this.content){
        let posts = 'posts/'+this.postsId;
        this.appFetch({
          url:posts,
          method:"PATCH",
          data: {
            "data": {
              "type": "posts",
              "attributes": {
                  "content": this.content,
                   'id':this.themeId
              }
            }
          }
        }).then((res)=>{
          // this.$router.push({
          //   path:'/details',
          //   name:'details',
          //   params: { themeId:this.themeId,postsId:postsId,themeContent:content}
          // })
          this.$router.push({ path:'details'+'/'+this.themeId});
        })
      } else {
        this.appFetch({
          url:"threads",
          method:"post",
          data:{
            "data": {
              "type": "threads",
              "attributes": {
                  "content": this.content,
              }
            }
          },
        }).then((res)=>{

        })

      }
    },
    //输入框自适应高度
    clearKeywords () {
      this.keywords = '';
      this.list = [];
      let textarea = this.$refs.textarea;
      let height = 40;
      let rem = height / rootFontSize;
      textarea.style.height = `${rem}rem`;
      rem = (height + 20) / rootFontSize;
      // this.$refs.list.style.height = `calc(100% - ${rem}rem)`;
      textarea.focus();
    },
    searchChange: debounce(function () {
      let trim = this.keywords && this.keywords.trim();
      if (!trim) {
        this.list = [];
        return;
      }
      const params = {
        keywords: this.keywords
      }
      // 调api ...
    }),
    handleFaceChoose (face) {
      const value = this.content
      const el = this.$refs.textarea
      const startPos = el.selectionStart
      const endPos = el.selectionEnd
      const newValue =
        value.substring(0, startPos) +
        face +
        value.substring(endPos, value.length)
      this.content = newValue
      if (el.setSelectionRange) {
        setTimeout(() => {
          const index = startPos + face.length
          el.setSelectionRange(index, index)
        }, 0)
      }
    },
    // handleKeyboardClick () {
    //   // this.showFacePanel = false;
    //   this.showFacePanel = true;
    //   // this.$refs.textarea.focus()
    // },
    addExpression(){
      this.keyboard = !this.keyboard;
      this.apiStore.find('emojis').then(data => {
        console.log(data);
        this.faceData = data.payload.data;
        // console.log(this.faceData);
      });

      this.showFacePanel = !this.showFacePanel;
      this.footMove = !this.footMove;
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
