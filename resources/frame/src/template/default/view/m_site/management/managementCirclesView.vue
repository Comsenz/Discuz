<!--移动端首页模板-->

<template>
  <div class="bgEd">
    <comHeader title="站点管理"></comHeader>
    <div class="content">
      <van-cell v-if="canEditUserGroup" title="成员管理" @click="managementCircles('members-management')" is-link />
      <van-cell v-if="canBatchEditThreads" title="批量管理" @click="managementCircles('delete')" is-link />
      <van-cell v-if="canCreateInvite" title="邀请成员" @click="managementCircles('invite-join')" class="borNone" is-link />
      <div class="lineE5"></div>
    </div>
  </div>
</template>

<style type="text/css" scoped>
	.bgEd { min-height: 100%; background: #EDEDED; }
</style>
<script>
import comHeader from '../../../view/m_site/common/loginSignUpHeader/loginSignUpHeader';
import mSiteManagementCirclesCon from '../../../controllers/m_site/management/managementCirclesCon';
import  '../../../scss/m_site/mobileIndex.scss';

export default {
    name: "managementCirclesView",
    components:{
    	comHeader
    },
    ...mSiteManagementCirclesCon
}



</script>
