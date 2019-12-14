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
      categories: [],
      categoriesId: [],
      cateId:'',
      content:'',
      showFacePanel: false,
      keyboard: false,
      // expressionShow: false,
      keywordsMax: 1000,
      list: [],
      footMove: false,
      faceData:[],
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
    if(this.$route.params.themeId){
      var themeId = this.$route.params.themeId;
      var postsId = this.$route.params.postsId;
      var themeContent = this.$route.params.themeContent;
      // console.log(themeId)
      this.themeId = themeId;
      this.postsId = postsId;
      this.content = themeContent;
    }
    //初始化请求分类接口
    this.loadCategories();
  },

  methods: {
    publish(){
      if(this.postsId && this.content){
        let posts = 'posts/'+this.postsId;
        this.appFetch({
          url:posts,
          method:"post",
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
          console.log('2222');
          let a = this.apiStore.pushPayload(res);
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
              },
              "relationships": {
                  "category": {
                      "data": {
                          "type": "categories",
                          "id": this.cateId
                      }
                  },
                  // "attachments": {
                  //     "data": [
                  //         {
                  //             "type": "attachments",
                  //             "id": 1
                  //         },
                  //         {
                  //             "type": "attachments",
                  //             "id": 2
                  //         }
                  //     ]
                  // }
              }

            }
          },
        }).then((res)=>{
          // console.log(res.readdata._data.id);
          // console.log('456');
          var postThemeId = res.readdata._data.id;
          this.$router.push({ path:'details'+'/'+postThemeId});
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
      const value = this.content;
      const el = this.$refs.textarea;
      const startPos = el.selectionStart;
      const endPos = el.selectionEnd;
      const newValue = value.substring(0, startPos) + face + value.substring(endPos, value.length)
      this.content = newValue
      if (el.setSelectionRange) {
        setTimeout(() => {
          const index = startPos + face.length
          el.setSelectionRange(index, index)
        }, 0)
      }
    },
    // handleKeyboardClick () {
    //   this.showFacePanel = false;
    //   this.$refs.textarea.focus();
    //   this.footMove = false;
    // },
    addExpression(){
      this.keyboard = !this.keyboard;
      this.appFetch({
        url: 'emojis',
        method: 'get',
        data: {
          include: '',
        }
      }).then((data) => {
        this.faceData = data.readdata;
      })
      this.showFacePanel = !this.showFacePanel;
      this.footMove = !this.footMove;
    },
    backClick() {
      this.$router.go(-1);
    },
    dClick() {
      this.showPopup = true;
    },
    onConfirm( value, index) {
      console.log(value);
      var id = value.id;
      this.cateId = id;
      console.log(this.cateId);
      var text = value.text;
      this.showPopup = false;
      this.selectSort = value.text;
    },

    loadCategories(){
      this.appFetch({
        url: 'categories',
        method: 'get',
        data: {
          include: '',
        }
      }).then((res) => {
        console.log(res, 'res1111');
        var newCategories = [];
        newCategories = res.readdata;
        console.log(res.readdata);
        for(let j = 0,len=newCategories.length; j < len; j++) {
          // console.log(newCategories[j]._data);
          this.categories.push(
            {
              'text': newCategories[j]._data.name,
              'id':newCategories[j]._data.id
            }
          );
          // console.log(this.categories)
          this.categoriesId.push(newCategories[j]._data.id);
        }
      })
    },
    onCancel() {
      this.showPopup = false;
    }

  }
}
