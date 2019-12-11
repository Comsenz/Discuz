
import Card from '../../../view/site/common/card/card';
import CardRow from '../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      picture:'',     //图片扩展名
      fileExtension:'',//文件扩展名
      maximumSize:''//最大尺寸
    }
  },
  created(){
    this.annexSet()
  },
  methods:{
    annexSet(){
      this.appFetch({
        url:'forum',
        method:'get',
        data:{
        }
      }).then(res=>{
        this.picture = res.readdata._data.supportImgExt;
        this.fileExtension = res.readdata._data.fileExtension;
        this.maximumSize = res.readdata._data.supportMaxSize;
        console.log(res)
      })
    },

    submi(){ //提交附件信息
      var reg = /^(?:[a-zA-Z]{3},)*[a-zA-Z]{3}$/;
      var regs = /^\d+$|^\d+[.]?\d+$/;
      var picture = this.picture;
      var fileExtension = this.fileExtension;
      var maximumSize = this.maximumSize;
    if(!picture){
      this.$toast('请您输入图片扩展名')
      return
    }
    if(!fileExtension){
      this.$toast('请您输入文件扩展名')
      return
    }
    if(!maximumSize){
      this.$toast('请您输入支持的最大尺寸')
      return
    }
    if(!reg.test(picture)){
      this.$toast('请输入正确的扩展名格式')
    }
    if(!regs.test(maximumSize)){
      this.$toast('请输入正确的支持最大尺寸格式')
    }
      this.appFetch({
        url:'settings',
        method:'post',
        data:{
          support_img_ext:this.picture,
          support_file_ext:this.fileExtension,
          support_max_size:this.maximumSize
        }
      }).then(data=>{
        this.$message('提交成功')
      }).catch(error=>{
        
      })
    }
  },
  components:{
    Card,
    CardRow
  }
}
