
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
        data:{}
      }).then(res=>{
        if (res.errors){
          this.$message.error(res.errors[0].code);
        }else {
          this.picture = res.readdata._data.set_attach.support_img_ext;
          this.fileExtension = res.readdata._data.set_attach.support_file_ext;
          this.maximumSize = res.readdata._data.set_attach.support_max_size;
        }
      })
    },

    submi(){ //提交附件信息
      var reg = /^(?:[a-zA-Z]{3},)*[a-zA-Z]{3}$/;
      var regs = /^\d+$|^\d+[.]?\d+$/;
      var regSize= /^[0-9]*$/;
      var picture = this.picture;
      var fileExtension = this.fileExtension;
      var maximumSize = this.maximumSize;
    if(!picture){
      this.$message.error('请您输入图片扩展名');
      return
    }
    if(!fileExtension){
      this.$message.error('请您输入文件扩展名');
      return
    }
    if(!maximumSize){
      this.$message.error('请您输入支持的最大尺寸');
      return
    }
    if(!regs.test(maximumSize)){
      this.$message.error('请输入正确的支持最大尺寸格式');
      return
    }
    if(!regSize.test(maximumSize)){
      this.$message.error('请输入正确的支持最大尺寸格式');
      return
    }
      this.appFetch({
        url:'settings',
        method:'post',
        data:{
          "data":[
            {
              "attributes":{
                "key":'support_img_ext',
                "value":this.picture,
                "tag": "default"
              }
            },
            {
              "attributes":{
                "key":'support_file_ext',
                "value":this.fileExtension,
                "tag": "default",
              }
              },
              {
                "attributes":{
                  "key":'support_max_size',
                  "value":this.maximumSize,
                  "tag": "default",
                }
              },

          ]
        }
      }).then(data=>{
        if (data.errors){
          this.$message.error(data.errors[0].code);
        }else {
          this.$message({message: '提交成功', type: 'success'});
        }
      }).catch(error=>{
      })
    }
  },
  components:{
    Card,
    CardRow
  }
}
