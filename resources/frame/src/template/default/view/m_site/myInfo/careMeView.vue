<template>
    <div class="searchBox">
      <comHeader title="关注我的人"></comHeader>
      <div class="content">
        <form action="/">
          <van-search
            v-model="searchVal"
            placeholder="搜索用户和主题"
            background="#f8f8f8"
            show-action
            @input="onSearch"
            @cancel="onCancel"
            class="searchCon"
          />
        </form>
        <div class="searchRes" v-show="searchUserList.length > 0">
          <div class="resUser" v-for="(item, index) in searchUserList" :key="index"  @click="jumpPerDet(item._data.id)">
            <img v-if="item.fromUser._data.avatarUrl" :src="item.fromUser._data.avatarUrl" class="resUserHead"  @click="jumpPerDet(item.fromUser._data.id)">
            <img v-else="" :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="resUserHead"  @click="jumpPerDet(item.fromUser._data.id)">
            <div class="resUserDet">
              <!-- <span class="resUserName">多少分接<i>你</i>口的是否健康的首付款觉得第三方第三方是的是的是的所舒服的</span> -->
              <!-- <span class="resUserName">{{item.username().slice(0,item.username().indexOf(searchVal))}}<i>{{searchVal}}</i>{{item.username().substr(item.username().indexOf(searchVal) + 1)}}</span> -->
               <span class="resUserName" v-html="item.fromUser._data.username.replace(searchVal,'<i>'+searchVal+'</i>')" ></span>
               <a href="javascript:;" class="alreadFollow" v-if="item._data.is_mutual == '0'" @click="followSwitch(item._data.is_mutual,item.toUser._data.id,index)">关注TA</a>
               <a href="javascript:;" class="alreadFollow" v-else="item._data.is_mutual == '1'" @click="followSwitch(item._data.is_mutual,item.toUser._data.id,index)">相互关注</a>
               <!-- <a href="javascript:;" class="followHe" v-else="item._data.is_mutual == '2'" @click="followSwitch('2',item.toUser._data.id,index)">关注TA</a> -->
              <!-- <span class="userRole">{{item.groups[0] && item.groups[0]._data.name}}</span> -->
            </div>
          </div>
        </div>
      </div>
    </div>
</template>
<style type="text/css" scoped>
  .searchBox { height: 100%; background: #EEEFEF; }
  .searchCon { box-shadow: 0px 1px rgba(0,0,0,0.02); margin-bottom: 1px;}
</style>
<script>
// import  '../../../scss/m_site/mobileIndex.scss';
import comHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import careMeCon from '../../../controllers/m_site/myInfo/careMeCon';
import  '../../../defaultLess/m_site/common/common.less';
import  '../../../defaultLess/m_site/modules/circle.less';
import  '../../../defaultLess/m_site/modules/search.less';
export default {
    name: "careMeView",
    components:{
    	comHeader,
    },
  ...careMeCon
}
</script>
