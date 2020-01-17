<!--移动端首页模板-->

<template>
    <div>
      <myInviteJoinHeader title="邀请加入"></myInviteJoinHeader>
	    <div class="content">
        <van-list
          v-model="loading"
          :finished="finished"
          :offset="offset"
          finished-text="没有更多了"
          @load="onLoad"
          :immediate-check="false"
        >
        <van-pull-refresh v-model="isLoading" @refresh="onRefresh">
            <div class="inviteBox">
              <table class="inviteTable">
                <tr>
                  <th>编号</th>
                  <th>邀请为</th>
                  <th>链接状态</th>
                  <th>链接操作</th>
                </tr>
                <tr v-for="(inviteLi, index) in inviteList" :key="index">
                  <td>{{inviteLi._data.id}}</td>
                  <td>{{getGroupNameById[inviteLi._data.group_id]}}</td>
                  <td>{{inviteLi._data.status === 0 ? '已失效' : '使用中'}}</td>
                  <td>
                    <a href="javascript:;" :class="['copyA', inviteLi._data.status === 0 && 'font9']" @click="copyToClipBoard(inviteLi)">复制</a>
                    <a href="javascript:;" :class="['invalidA', inviteLi._data.status === 0 && 'font9']" @click="resetDelete(inviteLi)">置为无效</a>
                  </td>
                </tr>
              </table>


            </div>
          </van-pull-refresh>
      </van-list>
	  </div>
		<div class="manageFootFixed" :style="{'width': (!isPhone && !isWeixin) ? '640px' : '100%','left': (!isPhone && !isWeixin) ? (viewportWidth - 640)/2+'px' : '0'}">
			<div class="operaCho">
				<div class="operaWo" @click="showChoice">
					<span>{{checkOperaStatus ? choiceRes.attributes.name + ' 邀请链接' : choiceRes.attributes.name}}</span>
					<i class="icon iconfont icon-choice-item"></i>
				</div>
				<ul class="operaChoList" v-if="choiceShow">
					<li v-for="(item, index) in choList" :key="index"  v-on:click.stop="setSelectVal(item)" class="operaChoLi">{{item.attributes.name}} 邀请链接</li>
				</ul>
			</div>
			<button class="checkSubmit" @click="checkSubmit">生成</button>
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
// import  '../../../scss/m_site/mobileIndex.scss';
import  '../../../defaultLess/m_site/common/common.less';
import  '../../../defaultLess/m_site/modules/manageCircle.less';
export default {
    name: "managementCirclesView",
    components:{
    	Header
    },
    ...mSiteHeader,
    ...mSiteInviteToJoinCon
}



</script>
