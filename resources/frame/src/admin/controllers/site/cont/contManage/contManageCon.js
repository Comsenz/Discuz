/*
* 内容分类控制器
* */

import Card from '../../../../view/site/common/card/card';
import ContArrange from '../../../../view/site/common/cont/contArrange';
import moment from 'moment';

export default {
  data:function () {
    return {
      operatingList:[
        {
          name:'批量移动到分类',
          label:'class'
        },
        {
          name:'批量置顶',
          label:'sticky'
        },
        {
          name:'批量删除',
          label:'delete'
        },
        {
          name:'批量设置精华',
          label:'marrow'
        }
      ],  //操作列表
      operatingSelect:'',  //操作单选选择

      categoriesList: [],   //选择圈子列表
      categoryId: '',  //选择圈子选中

      toppingRadio:2,  //是否置顶
      essenceRadio:2,   //是否精华

      checkAll: false,    //全选状态
      checkAllNum:0,      //多选打勾数
      checkedTheme:[],    //多选列表初始化
      isIndeterminate: false,   //全选不确定状态

      themeList:[]    //主题列表
    }
  },

  methods:{

    handleCheckAllChange(val) {
      if (val){
        this.checkedTheme.forEach((item,index)=>{
          this.checkedTheme[index].id = this.themeList[index].id();
          this.checkedTheme[index].status = true;
          this.checkAllNum = this.checkedTheme.length;
        })
      } else {
        this.checkedTheme.forEach((item,index)=>{
          this.checkedTheme[index].id = this.themeList[index].id();
          this.checkedTheme[index].status = false;
          this.checkAllNum = 0;
        })
      }

      this.isIndeterminate = false;
    },

    handleCheckedCitiesChange(index,id,status) {

      this.checkedTheme[index].id = id;

      let checkLength = this.checkedTheme.length;

      /*
      * 统计多选打勾数
      * */
      this.checkAllNum = status ? this.checkAllNum + 1 : this.checkAllNum - 1;

      /*
      * 如果打勾数大于 0 或小于 主题列表长度，则全选不确定状态打开
      * */
      if (this.checkAllNum > 0 && this.checkAllNum < checkLength){
        this.isIndeterminate = true;
      }

      /*
      * 如果打勾数等于主题列表长度，则全选状态打开，不确定状态关闭
      * */
      if (this.checkAllNum === checkLength){
        this.checkAll = true;
        this.isIndeterminate = false;
      }

      /*
      * 如果打勾数小于1，则全选状态、不确定状态都关闭
      * */
      if (this.checkAllNum < 1){
        this.isIndeterminate = false;
        this.checkAll = false;
      }

    },

    /*
    * 格式化日期
    * */
    formatDate(data){
      return moment(data).format('YYYY-MM-DD HH:mm')
    },

    selectChange(){
      console.log(this.categoryId);
    },

    submitClick(){

      let themeData = [];     //操作主题数据
      let attributes = {};    //操作选项

      switch (this.operatingSelect){
        case 'class':
          attributes.categoryId = this.categoryId;
          break;
        case 'sticky':
          attributes.isSticky = this.toppingRadio === 1? true : false;
          break;
        case 'delete':
          attributes.isDeleted = true;
          break;
        case 'marrow':
          attributes.isEssence = this.essenceRadio === 1? true : false;
          break;
        default:
          console.log('数据错误');
      }

      // console.log(attributes);

      this.checkedTheme.forEach((item,index)=>{
        console.log(item);
        if (item.status === true){
          themeData.push(
            {
              'type':'threads',
              'id':item.id,
              'attributes':attributes
            }
          )
        }
      });

      console.log(themeData);

      this.appFetch({
        url:'threads/batch',
        method:'patch',
        data:{data:themeData}
      }).then(res=>{
        console.log(res);
      })

    },

    /*
    * 请求接口
    * */
    getThemeList(){
      const params = {};
      params.include = 'user,firstPost,lastThreePosts,lastThreePosts.user,firstPost.likedUsers,rewardedUsers';
      this.apiStore.find('threads', params).then(data => {
        this.themeList = data;

        console.log(data.length);

        /*初始化主题多选框列表*/
        data.forEach(()=>{
          this.checkedTheme.push({
            id:'',
            status:false
          })
        });

      });
    },
    getCategories(){
      /*const params = {};
      params.include = 'user,firstPost,lastThreePosts,lastThreePosts.user,firstPost.likedUsers,rewardedUsers';
      this.apiStore.find('classify', params).then(data => {
        this.themeList = data;

        console.log(data.length);

        /!*初始化主题多选框列表*!/
        data.forEach(()=>{
          this.checkedTheme.push({
            id:'',
            status:false
          })
        });

      });*/

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
        })
      })

    },

    patchBatch(){

    }

  },

  mounted(){
    console.log(this.categoriesList);
  },
  created(){
    this.getThemeList();
    this.getCategories();

  },

  components:{
    Card,
    ContArrange
  }

}
