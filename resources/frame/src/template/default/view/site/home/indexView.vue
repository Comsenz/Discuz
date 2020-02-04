<!-- 网站首页模板 -->

<template>
	<div class="pc_index">
    <a href="javascript:;"  class=" weui-btn weui-btn_primary">页面主操作</a>
		<h3 class="h3-title">{{ title }}</h3>
		<p>{{ description }}</p>
		<p>
			<span>{{ num }}</span>
			<button @click="addNum">加1</button>
		</p>
		<div id="1">
			<p>

			</p>
			<p></p>
			<p></p>
		</div>
		<template v-if="voteInfo.body">
			<h3>投票列表</h3>
			<p>
				<ul>
					<li v-for="item in voteInfo.body.items" :key="index">
						<span>{{ item.description }}</span>
					</li>
				</ul>
			</p>
		</template>
		<p v-else>暂无投票</p>
		<div id="2">
			<p>

			</p>
			<p></p>
			<p></p>
		</div>
	</div>
</template>

<script>
// import '../../../scss/site/pcIndex.scss';
import siteIndexCon from '../../../controllers/site/home/indexCon';
export default {
	name: "siteIndex",
	...siteIndexCon
}
</script>
