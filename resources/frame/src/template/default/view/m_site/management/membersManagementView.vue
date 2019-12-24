<!--移动端首页模板-->

<template>
    <div>
		<div class="foueHeadBox">
			<div class="fourHeader">
		        <span class="icon iconfont icon-back headBack" @click="headerBack"></span>
		        <h1 class="headTit">{{$route.meta.title}}</h1>
		    </div>
		    <div class="serBox" v-show="serHide">
		    	<input type="text" name="" placeholder="搜索" class="serInp"   @click="serToggle">
		    	<!-- <i v-show="searchName === ''" class="icon iconfont icon-search"></i> -->
		    </div>
		<form action="/">
          <van-search
            v-model="searchName"
            v-show="serShow"
            ref="serInp"
            placeholder="搜索用户和主题"
            background="#f8f8f8"
            show-action
            @input="handleSearch"
            @cancel="onCancel"
            class="searchCon"
          />
        </form>
		</div>
		<van-list
		v-model="loading"
		:finished="finished"
		:offset="offset"
		finished-text="没有更多了"
		@load="onLoad"
		>
    <van-pull-refresh v-model="isLoading" @refresh="onRefresh">
	    <div class="searchRes memberCheckList">
	        <van-checkbox-group v-model="result">
			  <van-cell-group>
			    <van-cell
			      class="resUser"
			      v-for="item in userList"
			      clickable
			      :key="item._data.username"
			      @click="toggle(item._data.id)"
			    >
			    <img :src="item._data.avatarUrl" alt="" class="resUserHead">
			    <div class="resUserDet">
		            <span class="resUserName">{{item._data.username}}</span>
		            <span class="userRole">{{item.groups[0] && item.groups[0]._data.name}}</span>
		            <van-checkbox
		             class="memberCheck"
			        :name="item._data.username"
			        ref="checkboxes"
			        slot="right-icon"
			      />
		          </div>
			    </van-cell>
			  </van-cell-group>
			</van-checkbox-group>
			<!-- <div class="searchMore" v-show="userLoadMoreStatus" @click="handleLoadMoreUser">
				<i class="icon iconfont icon-search"></i>
				打开更多用户
			</div> -->
			<!-- </van-pull-refresh>
			</van-list> -->
	    </div>
 	</van-pull-refresh>    
  	</van-list>
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
	.van-list{
		padding-bottom: 1.2rem;
	}
	.memberCheckList{
		padding-bottom:0;
	}
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
