export default {
    data:function(){
        return{
            financialList:[
                {
                    title:"用户总充值",
                    num:"20,187",
                    icon:'iconchongzhi'
                },
                {
                    title:"用户总充提现",
                    num:"20,187",
                    icon:''
                },
                {
                    title:"用户钱包总金额",
                    num:"20,187",
                    icon:'iconqianbaozongjine'
                },
                {
                    title:"用户订单总数",
                    num:"20,187",
                    icon:''
                },
                {
                    title:"平台总盈利",
                    num:"20,187",
                    icon:''
                },
                {
                    title:"提现手续费收入",
                    num:"20,187",
                    icon:''
                },
                {
                    title:"打赏提成收入",
                    num:"20,187",
                    icon:'icondashangtichengshouru'
                },
                {
                    title:"注册加入收入",
                    num:"20,187",
                    icon:'iconzhucejiarushouru'
                },
                
            ],
            financialEcharts:null
           
        }
    },
    mounted(){
        this.earningsStatistics(); //盈利统计
    },
    methods:{
        earningsStatistics(){  //数据请求传给图标

        },
        earningsEcharts(){
            //初始化Echarts实例
            if(!this.earningsEcharts){
                this.earningsEcharts = this.$echarts.init(this.$refs.financial-profit-echartss)
            }
            //绘制图表
           var option = {
                title: {
                    text: '堆叠区域图'
                },
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'cross',
                        label: {
                            backgroundColor: '#6a7985'
                        }
                    }
                },
                legend: {
                    data: ['邮件营销', '联盟广告', '视频广告', '直接访问', '搜索引擎']
                },
                toolbox: {
                    feature: {
                        saveAsImage: {}
                    }
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                },
                xAxis: [
                    {
                        type: 'category',
                        boundaryGap: false,
                        data: ['周一', '周二', '周三', '周四', '周五', '周六', '周日']
                    }
                ],
                yAxis: [
                    {
                        type: 'value'
                    }
                ],
                series: [
                    {
                        name: '邮件营销',
                        type: 'line',
                        stack: '总量',
                        areaStyle: {},
                        data: [120, 132, 101, 134, 90, 230, 210]
                    },
                    {
                        name: '联盟广告',
                        type: 'line',
                        stack: '总量',
                        areaStyle: {},
                        data: [220, 182, 191, 234, 290, 330, 310]
                    },
                    {
                        name: '视频广告',
                        type: 'line',
                        stack: '总量',
                        areaStyle: {},
                        data: [150, 232, 201, 154, 190, 330, 410]
                    },
                    {
                        name: '直接访问',
                        type: 'line',
                        stack: '总量',
                        areaStyle: {},
                        data: [320, 332, 301, 334, 390, 330, 320]
                    },
                    {
                        name: '搜索引擎',
                        type: 'line',
                        stack: '总量',
                        label: {
                            normal: {
                                show: true,
                                position: 'top'
                            }
                        },
                        areaStyle: {},
                        data: [820, 932, 901, 934, 1290, 1330, 1320]
                    }
                ]
            };
        this.earningsEcharts.setOption(option);
    }
}
}
