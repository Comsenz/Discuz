/*
* 微信pc扫码控制器
* */

import webDb from "../../../../../helpers/webDbHelper";

export default {
  data:function(){
    return {
      siteName:'Discuz Q',    //站点名称
      wxUrl:'',               //微信扫码二维码
      sceneStr:'',            //微信scenestr
    }
  },
  created() {
    this.getWxUrl();
    this.siteName = webDb.getLItem('siteInfo')._data.set_site.site_name;
  },
  methods:{
    getWxUrl(){
      this.appFetch({
        url:'wxPcUrl',
        method:'get',
        data:{}
      }).then(res=>{
        if (res.errors) {
          if (res.errors[0].detail) {
            this.$toast.fail(res.errors[0].code + '\n' + res.errors[0].detail[0])
          } else {
            this.$toast.fail(res.errors[0].code);
          }
        } else {
          this.wxUrl = res.img;
          this.sceneStr = res.scene_str;

          let num = 0;

          const inQuire = setInterval(()=>{
            this.getUserStatus().then((data)=>{
              clearInterval(inQuire);
              if (data.errors) {
                if (data.errors[0].detail) {
                  this.$toast.fail(data.errors[0].code + '\n' + data.errors[0].detail[0])
                } else {
                  this.$toast.fail(data.errors[0].code);
                }

                let wxtoken = data.errors[0].token;

                if (data.rawData[0].code === 'no_bind_user') {
                  // this.wxtoken = wxtoken;
                  webDb.setLItem('wxtoken', wxtoken);
                  this.$router.push({path:'wx-login-bd'});
                }
              } else if (data.data.attributes.access_token) {

                this.$toast.success('登录成功');
                let token = data.data.attributes.access_token;
                let tokenId = data.data.id;
                let refreshToken = data.data.attributes.refresh_token;
                webDb.setLItem('Authorization', token);
                webDb.setLItem('tokenId', tokenId);
                webDb.setLItem('refreshToken', refreshToken);
                let beforeVisiting = webDb.getSItem('beforeVisiting');

                if (beforeVisiting) {
                  this.$router.replace({ path: beforeVisiting });
                  webDb.setSItem('beforeState', 1);
                } else {
                  this.$router.push({ path: '/' });
                }

              }
            });
            num ++;
            if (num > 5){
              clearInterval(inQuire);
            }
          },3000);


        }
      }).catch(err=>{
        console.log(err);
      })
    },
    getUserStatus(){
      return this.appFetch({
        url:"wxLoginStatus",
        method:"post",
        data:{
          'scene_str':this.sceneStr
        }
      }).then(res=>{
        // console.log(res);
        return res;
      }).catch(err=>{
        console.log(err);
      })
    }
  },
}
