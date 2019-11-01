<!--移动端首页模板-->

<template>
    <div>
		<Header></Header>
	    <div class="content">
	        <table class="inviteTable">
	        	<tr>
	        		<th>编号</th>
	        		<th>邀请为</th>
	        		<th>链接状态</th>
	        		<th>链接操作</th>
	        	</tr>
	        	<tr v-for="inviteLi in inviteList">
	        		<td>{{inviteLi.number}}</td>
	        		<td>{{inviteLi.role}}</td>
	        		<td>{{inviteLi.hrefStatus}}</td>
	        		<td>
	        			<a href="javascript:;" class="copyA">复制</a>
	        			<a href="javascript:;" class="invalidA">置为无效</a>
	        		</td>
	        	</tr>
	        	<!-- <tr>
	        		<td>2</td>
	        		<td>合伙人</td>
	        		<td>已失效</td>
	        		<td>
	        			<a href="javascript:;" class="copyA font9">复制</a>
	        			<a href="javascript:;" class="invalidA font9">置为无效</a>
	        		</td>
	        	</tr> -->
	        </table>
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
import Header from '../../m_site/common/headerView';
import mSiteHeader from '../../../controllers/m_site/common/headerCon';
import mSiteInviteToJoinCon from '../../../controllers/m_site/management/inviteToJoinCon';
import  '../../../scss/m_site/mobileIndex.scss';

export default {
    name: "managementCirclesView",
    components:{
    	Header
    },
    ...mSiteHeader,
    ...mSiteInviteToJoinCon
}



</script>
