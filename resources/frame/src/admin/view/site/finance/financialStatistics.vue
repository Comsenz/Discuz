<template>
<div class="financial-box">
<div class="financial-statistics">
    <div class="financial" v-for="(item,index) in financialList" :key="index">
        <div class="financial-head">
        <div class="financial-title">{{item.title}}</div>
        <span class="iconfont" :class="item.icon"></span>
        <span class="financial-con">¥<span class="financial-num">{{item.num}}</span></span>
        </div>
    </div>
</div>
</div>

</template>
<script>
import '../../../scss/site/module/financeStyle.scss';
import financialStatisticsCon from '../../../controllers/site/finance/financialStatisticsCon';
export default{
    name:"financial-statistics-view",
    ...financialStatisticsCon
}
</script>