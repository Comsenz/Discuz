
import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      categoriesList: [],   //主题分类列表
      categoryId: '',  //主题分类选中



      pageOptions: [
        {
          value: '选项1',
          label: '每页显示10条'
        }, {
          value: '选项2',
          label: '每页显示20条'
        }, {
          value: '选项3',
          label: '每页显示30条'
        }
      ],
      pageSelect:'',    //每页显示数选择值

      checkedStatus:false,  //更多选项

      pickerOptions: {
        shortcuts: [{
          text: '最近一周',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
            picker.$emit('pick', [start, end]);
          }
        }, {
          text: '最近一个月',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
            picker.$emit('pick', [start, end]);
          }
        }, {
          text: '最近三个月',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
            picker.$emit('pick', [start, end]);
          }
        }]
      },    //快捷选择时间

      dataValue:'',     //发表时间范围

      replyType:3,   //被回复数介于类型
      topType:6    //置顶主题类型

    }
  },
  methods:{
    checkboxChange(str){
      setTimeout(()=>{
        if (str){
          let gd =  document.getElementsByClassName('index-main-con__main')[0];
          gd.scrollTo(0,gd.scrollHeight);
        }
      },300);
    },

    submitClick(){
      this.appFetch({
        url:'pay',
        method:'post',
        splice:'/2019120310255349505652',
        data:{
          'payment_type':'10'
        }
      }).then(res=>{
        console.log(res);
      })
    },

    /*
    * 请求接口
    * */
    getCategories(){
      this.appFetch({
        url:'categories',
        method:'get',
        data:{}
      }).then(res=>{
        res.data.forEach((item,index)=>{
          this.categoriesList.push({
            name:item.attributes.name,
            id:item.id
          })
        });

        // console.log(this.categoriesList);

      })

    },
  },
  created(){
    this.getCategories();
  },
  components:{
    Card,
    CardRow
  }
}
