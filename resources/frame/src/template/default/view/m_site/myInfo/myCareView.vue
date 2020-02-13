<template>
    <div class="searchBox">
      <div class="content">
        <comHeader title="我关注的人"></comHeader>
        <form action="/">
          <van-search
            v-model="searchVal"
            placeholder="搜索我关注的人"
            background="#f8f8f8"
            @input="onSearch"
            @cancel="onCancel"
            class="searchCon"
          />
        </form>
        <div class="searchRes" v-if="searchResStatus">
          <div class="resUser" v-for="(item, index) in searchUserList" :key="index">
           <img v-if="item.toUser && item.toUser._data.avatarUrl" :src="item.toUser._data.avatarUrl" class="resUserHead"  @click="jumpPerDet(item.toUser._data.id)">
            <img v-else="" :src="appConfig.staticBaseUrl+'/images/noavatar.gif'" class="resUserHead"  @click="jumpPerDet(item.toUser._data.id)">
            <div class="resUserDet">
               <span class="resUserName" v-html="item.toUser && item.toUser._data.username.replace(searchVal,'<i>'+searchVal+'</i>')" ></span>
               <a href="javascript:;" class="alreadFollow" v-if="item._data.is_mutual == '0'" @click="followSwitch(item._data.is_mutual,item.toUser._data.id,index)">已关注</a>
               <a href="javascript:;" class="alreadFollow" v-else-if="item._data.is_mutual == '1'" @click="followSwitch(item._data.is_mutual,item.toUser._data.id,index)">相互关注</a>
               <a href="javascript:;" class="followHe" v-else="item._data.is_mutual == '2'" @click="followSwitch('2',item.toUser._data.id,index)">关注TA</a>
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
import comHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import myCareCon from '../../../controllers/m_site/myInfo/myCareCon';
import  '../../../defaultLess/m_site/common/common.less';
import  '../../../defaultLess/m_site/modules/circle.less';
import  '../../../defaultLess/m_site/modules/search.less';
export default {
    name: "myCarehView",
    components:{
    	comHeader,
    },
  ...myCareCon
}
</script>
