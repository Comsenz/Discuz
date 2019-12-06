<!--移动端首页模板-->

<template>
    <div>
		<div class="foueHeadBox">
			<div class="fourHeader">
		        <span class="icon iconfont icon-back headBack" ></span>
		        <h1 class="headTit">{{$route.meta.title}}</h1>
		    </div>
		    <div class="serBox">
		    	<input type="text" name="" placeholder="搜索" class="serInp" v-model="searchName" @input="handleSearch">
		    	<i v-show="searchName === ''" class="icon iconfont icon-search"></i>
		    </div>
		</div>
	    <div class="searchRes memberCheckList">
	        <van-checkbox-group v-model="result">
			  <van-cell-group>
			    <van-cell
			      class="resUser"
			      v-for="item in userList"
			      clickable
			      :key="item.username()"
			      @click="toggle(item.id())"
			    >
			    <img :src="item.avatarUrl()" alt="" class="resUserHead">
			    <div class="resUserDet">
		            <span class="resUserName">{{item.username()}}</span>
		            <span class="userRole">{{item.data.relationships.groups.data[0] ? getGroupNameById[item.data.relationships.groups.data[0].id] : ''}}</span>
		            <van-checkbox
		             class="memberCheck"
			        :name="item.username()"
			        ref="checkboxes"
			        slot="right-icon"
			      />
		          </div>
			    </van-cell>
			  </van-cell-group>
			</van-checkbox-group>
			<div class="searchMore" v-show="userLoadMoreStatus" @click="handleLoadMoreUser">
				<i class="icon iconfont icon-search"></i>
				搜索更多用户
			</div>
	    </div>
		<div class="manageFootFixed">
			<div class="operaCho">
				<div class="operaWo" @click="showChoice">
					<span>{{choiceRes.attributes.name}}</span>
					<i class="icon iconfont icon-choice-item"></i>
				</div>
				<ul class="operaChoList" v-if="choiceShow">
					<li v-for="item in choList" :key="item.id"  v-on:click.stop="setSelectVal(item)" class="operaChoLi">{{item.attributes.name}}</li>
				</ul>
			</div>
			<button class="checkSubmit" @click="handleSubmit">提交</button>
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
