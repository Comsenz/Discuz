
/**云api配置 */
import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      secretId:'',
      secretKey:'',
      appId:''
    }
  },
  created(){
    this.tencentCloudList()//初始化云api配置
  },
  methods:{
    configClick(type){

    },
    tencentCloudList(){
      this.appFetch({
        url:'forum',
        method:'get',
        data:{

        }
      }).then(res=>{
        console.log(res)
        this.secretId = res.readdata._data.qcloudSecretId
        this.secretKey = res.readdata._data.qcloudSecretKey
      })
    }
  },
  components:{
    Card,
    CardRow
  }
}
