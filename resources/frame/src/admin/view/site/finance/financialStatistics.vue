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
<div class="financial-profit">
    <div class="financial-profit-title">
        <span class="iconfont iconchongzhi"></span>
        <div class="financial-profit-titles">盈利统计</div>  
    </div>
    <div class="financial-profit-chart" ref="financialProfitEcharts">     
    </div>

</div>
<div class="financial-profit">
    <div class="financial-profit-title">
        <span class="iconfont iconchongzhi"></span>
        <div class="financial-profit-titles">订单统计</div>  
    </div>
    <div class="financial-profit-chart" ref="financialOrderEcharts">     
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