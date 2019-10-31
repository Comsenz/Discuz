<!--移动端首页模板-->

<template>
    <div class="circleCon">
	    <Header></Header>
	    <div class="content">
	    	<van-cell title="URL 跳转" is-link url="/vant/mobile.html" />
			<van-cell title="路由跳转" is-link to="index" />
	    </div>
	    
    </div>
</template>

<script>
// import mSiteHeaderCon from '../../../controllers/m_site/common/headerCon';
// import '../../../vantJS/m_site/tabs/Title.js';
import Header from '../../m_site/common/headerView';
import mSiteHeader from '../../../controllers/m_site/common/headerCon';
import mSitManagementCirclesCon from '../../../controllers/m_site/management/managementCirclesCon';

import  '../../../scss/m_site/mobileIndex.scss';

export default {
    name: "managementCirclesView",
    components:{
    	Header
    },
    ...mSiteHeader,
    ...mSitManagementCirclesCon
}



</script>
