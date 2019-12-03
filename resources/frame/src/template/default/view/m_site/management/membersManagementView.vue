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
	    <div class="searchRes memberCheckList" v-show="flag">
	        <van-checkbox-group v-model="result">
			  <van-cell-group>
			    <van-cell
			      class="resUser"
			      v-for="(item, index) in list"
			      clickable
			      :key="item"
			      @click="toggle(index)"
			    >
			    <img src="../../../../../../static/images/noavatar.gif" class="resUserHead">
			    <div class="resUserDet">
		            <span class="resUserName">{{item.user().username()}}</span>
		            <span class="userRole">合伙人</span>
		            <van-checkbox
		             class="memberCheck"
			        :name="item"
			        ref="checkboxes"
			        slot="right-icon"
			      />
		          </div>
			    </van-cell>
			  </van-cell-group>
			</van-checkbox-group>
	    </div>
		<div class="manageFootFixed">
			<div class="operaCho">
				<div class="operaWo" @click="showChoice">
					<span v-model="choiceRes">{{choiceRes}}</span>
					<i class="icon iconfont icon-choice-item"></i>
				</div>
				<ul class="operaChoList" v-if="choiceShow">
					<li v-for="(item, index) in choList" :key="index"  v-on:click.stop="setSelectVal(item)" class="operaChoLi">{{item}}</li>
				</ul>
			</div>
			<button class="checkSubmit">提交</button>
		</div>
	    
	    
    </div>
</template>
<style type="text/css" scoped>
	.bgEd { min-height: 100%; background: #EDEDED; }
</style>
<script>
// import Header from '../../m_site/common/headerView';
// import mSiteHeader from '../../../controllers/m_site/common/headerCon';
import mSiteMembersManagementCon from '../../../controllers/m_site/management/membersManagementCon';
import  '../../../scss/m_site/mobileIndex.scss';

export default {
    name: "managementCirclesView",
    components:{
    	// Header
    },
    // ...mSiteHeader,
    ...mSiteMembersManagementCon
}



</script>
