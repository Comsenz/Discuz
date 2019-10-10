<!-- 网站手机端首页模板 -->

<template>
	<div class="site_index">
		<h3>{{ title }}</h3>
		<p>{{ description }}</p>
		<p>
			<span>{{ num }}</span>
			<button @click="addNum">加1</button>
		</p>
	</div>
</template>

<script>
import mSiteIndexCon from 'mSiteIndexCon';
import "mobileIndex.scss";

export default {
	name: "siteIndex",
	...mSiteIndexCon
}
</script>