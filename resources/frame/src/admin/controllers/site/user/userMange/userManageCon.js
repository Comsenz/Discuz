/*
* 用户管理
* */

import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';

export default {
  data:function () {
    return {
      options: [],
      username: '',
      userUID: '',
      userRole: [],
      checked:false,
      userPhone: '',
      radio1:'0',
    }
  },

  created(){
    this.getUserList();
  },

  methods:{
    checkedStatus(str){
      setTimeout(()=>{
        if (str){
          let gd =  document.getElementsByClassName('index-main-con__main')[0];
          gd.scrollTo(0,gd.scrollHeight);
        }
      },300);
    },
    searchBtn(){
      let query = {
        username: this.username.trim(),
        userUID: this.userUID.trim(),
        userRole: this.userRole,
        userPhone: this.userPhone.trim(),
        radio1: this.radio1,
      };
      if(!this.checked){
        this.userPhone = '';
        this.radio1 = '0';

        if(query.username + query.userUID + query.userRole === ''){
          query = {};
        } else {
          delete query.userPhone;
          delete query.radio1;
        }
      }
      this.$router.push({path:'/admin/user-search-list', query})
    },

    async getUserList(){
      try{
        const response = await this.appFetch({
          method: 'get',
          url: 'groups'
        })
        const data = response.data;
        this.options = data.map((v)=>{
          return {
              value: v.id,
              label: v.attributes.name
          }
        })
      } catch(err){
        console.error(err, 'getUserList')
      }
    },

  },

  components:{
    Card,
    CardRow
  }
}
