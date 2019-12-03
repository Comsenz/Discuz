import {Bus} from '../../../store/bus.js';
import { debounce, autoTextarea } from '../../../../../common/textarea.js';
let rootFontSize = parseFloat(document.documentElement.style.fontSize);
export default {
  data:function () {
    return {
      headerTitle:"回复主题",
      content:'',
      showFacePanel: false,
      keyboard: false,
      replyText:'',
      keywordsMax: 1000,
      footMove: false,
      faceData:[],
      fileList: [
        { url: 'https://img.yzcdn.cn/vant/leaf.jpg' }
        // Uploader 根据文件后缀来判断是否为图片文件
        // 如果图片 URL 中不包含类型信息，可以添加 isImage 标记来声明
        // { url: 'https://cloud-image', isImage: true }
      ],
      replyId:'',
      themeId:''
    }
  },
  created(){
    var replyQuote = this.$route.params.replyQuote;
    var replyId = this.$route.params.replyId;
    var themeId = this.$route.params.themeId;
    console.log(replyQuote);
    console.log(replyId);
    console.log(themeId+'2222');
    this.replyText = '<blockquote class="quoteCon">'+replyQuote+'</blockquote>';
    this.replyId = replyId;
    this.themeId = themeId;
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
  beforeDestroy () {
      Bus.$off('message');
  },
  methods: {
    //回复主题
    publish(){
      if(this.replyId && this.replyText){
        this.appFetch({
          url:"posts",
          method:"post",
          data:{
            "data": {
                "type": "posts",
                "attributes": {
                    "replyId": this.replyId,
                    "content": this.replyText
                },
                "relationships": {
                    "thread": {
                        "data": {
                            "type": "threads",
                            "id": this.themeId
                        }
                    }
                }
            }
          },
        }).then(res =>{
          this.$router.push({path:'details'+'/'+this.themeId})
        })
      } else {
        this.appFetch({
          url:"posts",
          method:"post",
          data:{
            "data": {
                "type": "posts",
                "attributes": {
                    "content": "{{$randomWords}} == {{$randomColor}} == {{$randomWords}}"
                },
                "relationships": {
                    "thread": {
                        "data": {
                            "type": "threads",
                            "id": "4"
                        }
                    }
                }
            }
          },
        }).then(res =>{
          this.$router.push({path:'details'+'/'+this.themeId})
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
      let trim = this.keywords.trim();
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
    addExpression(){
      this.keyboard = !this.keyboard;
      this.apiStore.find('emojis').then(data => {
        // console.log(data);
        this.faceData = data.payload.data;
        // console.log(this.faceData);
      });

      this.showFacePanel = !this.showFacePanel;
      this.footMove = !this.footMove;
    },
    backClick() {
      this.$router.go(-1);
    },
  }
}
