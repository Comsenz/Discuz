<!--移动端首页模板-->

<template>
    <div class="bgEd">
	    <Header></Header>
	    <div class="content">
	    	<van-cell title="成员管理" @click="managementCircles('members-management')" is-link url="/vant/mobile.html" />
			<van-cell title="批量管理" @click="managementCircles('delete')" is-link to="index" />
			<van-cell title="邀请成员"  @click="managementCircles('circle-manage-invite')"  is-link to="index" class="borNone"/>
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
