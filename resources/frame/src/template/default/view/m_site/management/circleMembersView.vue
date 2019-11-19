<!--移动端首页模板-->

<template>
    <div>
      <div class="foueHeadBox">
        <div class="fourHeader">
              <span class="icon iconfont icon-back headBack" ></span>
              <h1 class="headTit">{{$route.meta.title}}</h1>
          </div>
          <div class="serBox">
            <input type="text" name="" placeholder="搜索" class="serInp">
            <i class="icon iconfont icon-search"></i>
          </div>
      </div>
	    <div class="searchRes">
		  	<van-cell-group class="cirMemberList">
			    <van-cell
			      class="resUser"
			      v-for="(item, index) in list"
			      :key="item"
			      @click="toggle(index)"
			    >
			    <img src="../../../../../../static/images/noavatar.gif" class="resUserHead">
			    <div class="resUserDet">
		            <span class="resUserName">小<i>虫</i></span>
		            <span class="userRole">合伙人</span>
		        </div>
			    </van-cell>
		  	</van-cell-group>
	    </div>
    </div>
</template>
<style type="text/css" scoped>
	.bgEd { min-height: 100%; background: #EDEDED; }
</style>
<script>
import '../../../less/m_site/myInfo/myInfo.less';
import mSiteCircleMembersCon from '../../../controllers/m_site/management/circleMembersCon';
import  '../../../scss/m_site/mobileIndex.scss';

export default {
    name: "managementCirclesView",
    components:{
    	// Header
    },
    // ...mSiteHeader,
    ...mSiteCircleMembersCon
}



</script>
