/**
 * 发布主题控制器
 */
import { debounce, autoTextarea } from '../../../../../common/textarea.js';
import Store from '../../../../../common/Store.js';
import Post from '../../../../../common/models/Post.js';
import appCommonH from '../../../../../helpers/commonHelper';
let rootFontSize = parseFloat(document.documentElement.style.fontSize);
export default {
  data:function () {
    return {
      headerTitle:"编辑主题",
      selectSort:'',
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
        // Uploader 根据文件后缀来判断是否为图片文件
        // 如果图片 URL 中不包含类型信息，可以添加 isImage 标记来声明
        // { url: 'https://cloud-image', isImage: true }
      ],
      uploadShow:false,
      enclosureList:[
        // {
        //   type:'doc',
        //   name:'saaaaaaaa',
        // },
      ],
      avatar: "",
      postsId:'',
      files: {
        name: "",
        type: ""
      },
      headerImage: null,
      picValue: null,
      upImgUrl:'',
      enclosureShow: false,
      isWeixin: false,
      isPhone: false,
      themeCon:false,
      attriAttachment:false,
      fileLength:0
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
      //设置在pc的宽度
      if(this.isWeixin != true && this.isPhone != true){
        this.limitWidth();
      }
  },
  computed: {
      themeId: function(){
          return this.$route.params.themeId;
      }
  },
  created(){
    this.isWeixin = appCommonH.isWeixin().isWeixin;
    this.isPhone = appCommonH.isWeixin().isPhone;
    //初始化请求分类接口
    this.loadCategories();
    //初始化请求主题数据
    this.detailsLoad();
  },
  watch: {

  },
  methods: {
      //初始化请求编辑主题数据
      detailsLoad(){
          this.appFetch({
            url: 'threads',
            method: 'get',
            splice:'/'+this.themeId,
            data: {
              include: ['firstPost',  'firstPost.images', 'firstPost.attachments', 'category'],
            }
          }).then((res) => {
            if (res.errors){
              this.$toast.fail(res.errors[0].code);
              throw new Error(res.error)
            } else {
              console.log(res);
              console.log('1234');
              const enclosureListCon = res.readdata.firstPost.attachments;
              const fileListCon = res.readdata.firstPost.images;
              console.log(fileListCon);
              this.cateId = res.readdata.category._data.id;
              // console.log(this.cateId);
              this.selectSort = res.readdata.category._data.name;
              this.content = res.readdata.firstPost._data.content;
              this.postsId = res.readdata.firstPost._data.id;
              for (let i = 0; i < enclosureListCon.length; i++) {
                this.enclosureList.push({type:enclosureListCon[i]._data.extension,name:enclosureListCon[i]._data.fileName,id:enclosureListCon[i]._data.id});
              }
              // console.log(this.enclosureList);
              if(this.enclosureList.length>0){
                this.enclosureShow = true;
              }
              for (let i = 0; i < fileListCon.length; i++) {
                this.fileListOne.push({thumbUrl:fileListCon[i]._data.thumbUrl,id:fileListCon[i]._data.id});
              }

              if(this.fileListOne.length>0){
                this.uploadShow = true;
              }
              // console.log(this.fileListOne);
              // console.log('999');
            }
          })
    },
    //发布主题
    publish(){
      this.attriAttachment = this.fileList.concat(this.enclosureList);
      for(let m=0;m<this.attriAttachment.length;m++){
        this.attriAttachment[m] = {
          "type": "attachments",
          "id": this.attriAttachment[m].id
        }
      }
      this.appFetch({
        url:'posts',
        method:"patch",
        splice:'/'+this.postsId,
        // data: {
        //   "data": {
        //     "type": "posts",
        //     "attributes": {
        //         "content": this.content
        //     }
        //   },
        // }

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
              "attachments": {
                "data":this.attriAttachment
              },
            }
          }
        },
      }).then((res)=>{
        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        } else {
          this.$router.push({ path:'/details'+'/'+this.themeId});
        }
      })
    },

    //设置底部在pc里的宽度
    limitWidth(){
      document.getElementById('post-topic-footer').style.width = "640px";
      let viewportWidth = window.innerWidth;
      document.getElementById('post-topic-footer').style.marginLeft = (viewportWidth - 640)/2+'px';
    },
    //上传图片,点击加号时
    handleFile(e){
      // 实例化
      // console.log(e);
      let formdata = new FormData()
      formdata.append('file', e.file);
      formdata.append('isGallery', 1);
      // console.log(this.fileList);
      this.uploaderEnclosure(formdata,false,true);
      this.loading = false;

    },
    //上传图片，点击底部Icon时
    handleFileUp(e){
      let file = e.target.files[0];
      let formdata = new FormData();
      formdata.append('file', file);
      formdata.append('isGallery', 1);
      this.uploaderEnclosure(formdata,true,false);
      this.uploadShow = true;
      this.loading = false;
    },
   
    //删除附件
    deleteEnclosure(id,type){
      console.log(id);
      return false;
      if(this.fileList.length<=1){
        this.uploadShow = false;
      }

      this.appFetch({
        url:'attachment',
        method:'delete',
        splice:'/'+id

      }).then(data=>{
        if (data.errors){
          this.$toast.fail(data.errors[0].code);
          throw new Error(data.error)
        } else {
          if(type == "img"){
            var newArr = this.fileList.filter(item => item.id !== id);
            this.fileList = newArr;
          } else {
            var newArr = this.enclosureList.filter(item => item.id !== id);
            this.enclosureList = newArr;

            var attriAttachment = new Array();
            for(var k=0;k<this.enclosureList.length;k++){
              var data = {};
              data.type = 'attachments';
              data.id = this.enclosureList[k].id;
              attriAttachment.push(data);
            }
            this.attriAttachment = attriAttachment;
          }
          this.$toast.success('删除成功');
        }
      })
    },

    //上传附件
    handleEnclosure(e){
      let file = e.target.files[0];
      let formdata = new FormData();
      formdata.append('file', file);
      formdata.append('isGallery', 0);
      // this.uploaderEnclosure(formdata,false,true);
      this.uploaderEnclosure(formdata,false,false,true);
    },
    //这里写接口，上传
    // uploaderEnclosure(file,isFoot,enclosure){
    uploaderEnclosure(file,isFoot,img,enclosure){
      console.log(file,isFoot,enclosure)
       this.appFetch({
         url:'attachment',
         method:'post',
         data:file,

       }).then(data=>{
         if (data.errors){
           this.$toast.fail(data.errors[0].code);
           throw new Error(data.error)
         }else{
            console.log(data);
            if(img){
              this.fileList.push({url:data.readdata._data.url,id:data.readdata._data.id});
              this.fileListOne[this.fileListOne.length-1].id = data.data.attributes.id;
              console.log(this.fileListOne);
            }
            if(isFoot){
              console.log('图片');
              this.fileListOne.push({url:data.readdata._data.url,id:data.readdata._data.id});
            }

             if(enclosure){
               console.log('fujian');
               this.enclosureShow = true;
               this.enclosureList.push({type:data.readdata._data.extension,name:data.readdata._data.fileName,id:data.readdata._data.id});
             }
            this.$toast.success('提交成功');
         }
       })
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

        if (res.errors){
          this.$toast.fail(res.errors[0].code);
          throw new Error(res.error)
        } else {
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
        }
      })
    },
    onCancel() {
      this.showPopup = false;
    }

  }
}
