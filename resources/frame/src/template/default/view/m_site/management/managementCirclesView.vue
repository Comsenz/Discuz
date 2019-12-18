<!--移动端首页模板-->

<template>
    <div class="bgEd">
	    <Header></Header>
	    <div class="content">
	    	<van-cell title="成员管理" @click="managementCircles('members-management')" is-link  />
			<van-cell title="批量管理" @click="managementCircles('delete')" is-link  />
			<van-cell title="邀请成员"  @click="managementCircles('invite-join')"  class="borNone" is-link/>
			<div class="lineE5"></div>
	    </div>
	    
    </div>
</template>
<style type="text/css" scoped>
	.bgEd { min-height: 100%; background: #EDEDED; }
</style>
<script>
import Header from '../../m_site/common/headerView';
import mSiteHeader from '../../../controllers/m_site/common/headerCon';
import mSiteManagementCirclesCon from '../../../controllers/m_site/management/managementCirclesCon';
import  '../../../scss/m_site/mobileIndex.scss';

export default {
    name: "managementCirclesView",
    components:{
    	Header
    },
    ...mSiteHeader,
    ...mSiteManagementCirclesCon
}



</script>
