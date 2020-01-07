
import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      loginStatus:'default',   //default h5 applets pc
      appId:'',
      appSecret:'',
      type:''
    }
  },
  created(){
    var type = this.$route.query.type;
    this.type = type;
    console.log(this.type);
    this.loadStatus();
  },
  methods:{
    loadStatus(){
      console.log(this.type);
      //��ʼ�����ò���
      this.appFetch({
        url:'tags',
        method:'get',
        splice:'/'+this.type,
        data:{
        }
      }).then(data=>{
          // console.log(data);
        if (data.errors){
          this.$message.error(data.errors[0].code);
        }else {
          this.appId = data.readdata[0]._data.app_id;
          this.appSecret = data.readdata[0]._data.app_secret;
        }
      }).catch(error=>{
        // console.log('ʧ��');
      })
    },
    submitConfiguration(){
      this.appFetch({
        url:'settings',
        method:'post',
        data:{
          "data":[
            {
               "attributes":{
                "key":"app_id",
                "value":this.appId,
                "tag": this.type
               }
             },
             {
               "attributes":{
                "key":"app_secret",
                "value":this.appSecret,
                "tag": this.type
               }
            }
          ]
        }
      }).then(data=>{
        if (data.errors){
          this.$toast.fail(data.errors[0].code);
        }else {
          // this.$router.push({
          //   path: '/admin/worth-mentioning-set'
          // });
          this.$message({
            message: '提交成功',
            type: 'success'
          });
        }
      })
    }
  },
  components:{
    Card,
    CardRow
  }
}
