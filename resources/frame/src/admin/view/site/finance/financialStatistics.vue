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
        <div class='financial-profit-title-left'>
            <span class="iconfont iconchongzhi"></span>
            <span class="financial-profit-titles">盈利统计</span>
        </div>
        <!-- <div class='financial-profit-title-right'> -->
            <div class="block">
            <span class="demonstration">周</span>
            <el-date-picker
            v-model="financialValue1"
            type="week"
            format="yyyy 第 WW 周"
            placeholder="选择周">
            </el-date-picker>
         </div>
             <div class="block">
                <span class="demonstration">月</span>
                <el-date-picker
                v-model="financialValue2"
                type="month"
                placeholder="选择月">
                </el-date-picker>
            </div>
            <el-date-picker
                v-model="financialTime"
                clearable
                type="daterange"
                value-format="yyyy-MM-dd"
                range-separator="至"
                start-placeholder="开始日期"
                end-placeholder="结束日期"
                @change='change'
                :picker-options="pickerOptions">
            </el-date-picker>
        <!-- </div> -->

    </div>
    <div class="financial-profit-chart" ref="financialProfitEcharts">     
    </div>

</div>
<div class="financial-order">
    <div class="financial-profit-title">
    <div class='financial-profit-title-left'>
        <span class="iconfont iconchongzhi"></span>
        <span class="financial-profit-titles">订单统计</span> 
    </div>
           <div class="block">
            <span class="demonstration">周</span>
            <el-date-picker
            v-model="orderValue1"
            type="week"
            format="yyyy 第 WW 周"
            placeholder="选择周">
            </el-date-picker>
         </div>
             <div class="block">
                <span class="demonstration">月</span>
                <el-date-picker
                v-model="orderValue2"
                type="month"
                placeholder="选择月">
                </el-date-picker>
            </div>
        <el-date-picker
            v-model="financialTime"
            clearable
            type="daterange"
            value-format="yyyy-MM-dd"
            range-separator="至"
            start-placeholder="开始日期"
            end-placeholder="结束日期"
            :picker-options="pickerOptions">
        </el-date-picker> 
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