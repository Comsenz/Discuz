export default {
    data: function () {
        return {
            financialList: [
                {
                    title: "用户总充值",
                    num: "20,187",
                    icon: 'iconchongzhi',
                    key: 'totalIncome',

                },
                {
                    title: "用户总充提现",
                    num: "20,187",
                    icon: 'icontixian',
                    key: 'totalWithdrawal',

                },
                {
                    title: "用户钱包总金额",
                    num: "20,187",
                    icon: 'iconqianbaozongjine',
                    key: 'totalWallet',

                },
                {
                    title: "用户订单总数",
                    num: "20,187",
                    icon: 'icondingdanzongshu',
                    key: 'orderCount',

                },
                {
                    title: "平台总盈利",
                    num: "20,187",
                    icon: 'iconcaiwutongji',
                    key: 'totalProfit',

                },
                {
                    title: "提现手续费收入",
                    num: "20,187",
                    icon: 'iconshouxufeishouru',
                    key: 'withdrawalProfit',

                },
                {
                    title: "打赏提成收入",
                    num: "20,187",
                    icon: 'icondashangtichengshouru',
                    key: 'orderRoyalty',

                },
                {
                    title: "注册加入收入",
                    num: "20,187",
                    icon: 'iconzhucejiarushouru',
                    key: 'totalRegisterProfit',

                },

            ],
            financialEcharts: null,    //盈利图标
            financiaOrderEchart: null, //订单图标
            pickerOptions: {
                shortcuts: [{
                    text: '最近一周',
                    onClick(picker) {
                        const end = new Date();
                        const start = new Date();
                        start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
                        picker.$emit('pick', [start, end]);
                    }
                }, {
                    text: '最近一个月',
                    onClick(picker) {
                        const end = new Date();
                        const start = new Date();
                        start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
                        picker.$emit('pick', [start, end]);
                    }
                }, {
                    text: '最近三个月',
                    onClick(picker) {
                        const end = new Date();
                        const start = new Date();
                        start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
                        picker.$emit('pick', [start, end]);
                    }
                }]
            },
            financialTime: ['', ''],  //申请时间
            orderTime: ['', ''],      //申请时间
            valueMouth:['',''],       //盈利按月统计时间
            valueOrder:['',''],       //订单按月统计时间
            noData: false,            //暂无数据
            noDataOrder: false,       //订单暂无数据
            istrue: 0,                
            istrueOder: 0,
            mouthTab: false,         //按月统计组件
            dayTab: true,           //按日统计
            mouthOrderTab:false,    //订单按月统计
            dayOderTab:true,        //订单按日统计
            indexOrderTab:false,    //订单切换
            indexStatistics:false,  //盈利切换
            items: [
                { name: '按日统计', index: 1 },
                { name: '按周统计', index: 2 },
                { name: '按月统计', index: 3 }
            ]

        }
    },
    created() {
        this.statistic()           //获取资金概况
    },
    mounted() {
        this.earningsStatistics(); //盈利统计
        this.orderStatistics();   //订单统计
    },
    methods: {
        tab(index) {             //盈利统计切换日期
            this.istrue = index
            if (index == 0 || index == 1) {
                this.dayTab = true;
                this.mouthTab = false;
                this.indexStatistics = false;
            }
            if (index == 2) {
                this.mouthTab = true
                this.dayTab = false;
                this.indexStatistics = true;
            }
            this.earningsStatistics();
        },
        tabOrder(index){        //订单统计切换日期
          this.istrueOder = index
          if(index == 0 || index == 1){
            this.dayOderTab = true;
            this.mouthOrderTab = false
            this.indexOrderTab = false;
          }
          if(index == 2){
            this.mouthOrderTab = true;
            this.dayOderTab = false ;
            this.indexOrderTab = true;
          }
          this.orderStatistics();
        },
        statistic() {
            this.appFetch({
                url: 'statistic',
                method: 'get',
                data: {

                }
            }).then(res => {
                console.log(res)
                // this.financialList = res.readdata._data;
                var oArr = Object.entries(res.readdata._data);
                for (var i = 0; i < this.financialList.length; i++) {
                    for (var j = 0; j < oArr.length; j++) {
                        if (this.financialList[i].key == oArr[j][0]) {
                            this.financialList[i].num = oArr[j][1];
                        }
                    }
                }
            })
        },
        change() {   //盈利统计日\周
            this.earningsStatistics();
        },
        changeOrder() {  //订单统计日\周
            this.orderStatistics();
        },
        changeMouth(){   //盈利统计月
            if (this.valueMouth == null) {
                this.valueMouth = ['', '']
            } else if (this.valueMouth[0] !== '' && this.valueMouth[1] !== '') {
                this.valueMouth[0] = this.valueMouth[0] + '-00-00-00';
                this.valueMouth[1] = this.valueMouth[1] + '-24-00-00';
            }
            // this.currentPaga = 1;
            this.earningsStatistics();
        },
        changeOrderMouth(){   //订单统计月
            this.orderStatistics();
        },
        earningsStatistics() {  //数据请求传给图标
            console.log(this.financialTime,'是不是数组')
            var dataStatistics ={    //盈利统计按日、周统计
                    'filter[type]': this.istrue + 1,
                    'filter[createdAtBegin]': this.financialTime[0],
                    'filter[createdAtEnd]': this.financialTime[1],
            }
            var dataStatisticsMouth = {    //盈利统计按月统计
                'filter[type]': this.istrue + 1,
                'filter[createdAtBegin]': this.valueMouth[0],
                'filter[createdAtEnd]': this.valueMouth[1],
            }
            var data;
            if(this.indexStatistics == false){
                data = dataStatistics
            }else{
                data = dataStatisticsMouth
            }
            this.appFetch({
                url: 'statisticChart',
                method: 'get',
                data:data
            }).then(res => {
                console.log(res, '盈利数据图标')
                if (res.readdata == '') {
                    this.noData = true
                }else{
                    this.noData = false  
                }
                var date = [];
                var total_profit = [];
                var withdrawal_profit = [];
                var master_portion = [];
                var register_profit = [];
                res.readdata.map(item => {
                    date.push(item._data.date)
                    total_profit.push(item._data.total_profit)
                    withdrawal_profit.push(item._data.withdrawal_profit)
                    master_portion.push(item._data.master_portion)
                    register_profit.push(item._data.register_profit)
                    // console.log(this.date,'000000')
                })
                this.earningsEcharts(date, total_profit, withdrawal_profit, master_portion, register_profit)

            })
        },
        orderStatistics() {  //订单数据请求
            var dataDay = {
                'filter[type]': this.istrueOder + 1,
                'filter[createdAtBegin]': this.orderTime[0],
                'filter[createdAtEnd]': this.orderTime[1],
            }
            var dataMouth = {
                'filter[type]': this.istrueOder + 1,
                'filter[createdAtBegin]': this.valueOrder[0],
                'filter[createdAtEnd]': this.valueOrder[1],
            }
            var data ;
            if(this.indexOrderTab == false){
                data = dataDay
            }
            if(this.indexOrderTab == true){
                data = dataMouth
            }
            this.appFetch({
                url: 'statisticChart',
                method: 'get',
                data: data
            }).then(res => {
                if (res.readdata == '') {
                    this.noDataOrder = true
                }else{
                    this.noDataOrder = false
                }
                    var date = [];
                    var order_count = [];
                    var order_amount = [];
                    res.readdata.map(item => {
                        date.push(item._data.date);
                        order_count.push(item._data.order_count);
                        order_amount.push(item._data.order_amount);
    
                    })
                    this.orderEcharts(date, order_count, order_amount)
   
            })

        },
        earningsEcharts(date, total_profit, withdrawal_profit, master_portion, register_profit) {
            //初始化Echarts实例
            if (!this.financialEcharts) {
                this.financialEcharts = this.$echarts.init(this.$refs.financialProfitEcharts)
            }
            //绘制图表
            var option = {
                title: {
                    // text: '堆叠区域图'
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
                    data: ['平台总盈利', '提现手续费收入', '打赏提成收入', '注册加入收入']
                },
                grid: {
                    left: '1%',
                    right: '6%',
                    bottom: '3%',
                    containLabel: true
                },
                xAxis: [
                    {
                        type: 'category',
                        boundaryGap: false,
                        data: date,
                        axisLabel: {
                            interval: 0, 
                             rotate:-40 
                            },
                    }, 
                       
                ],

                yAxis: [
                    {
                        type: 'value'
                    }
                ],
                series: [
                    {
                        name: '平台总盈利', //total_profit
                        type: 'line',
                        stack: '总量',
                        areaStyle: {},
                        data: total_profit
                    },
                    {
                        name: '提现手续费收入', //withdrawal_profit
                        type: 'line',
                        stack: '总量',
                        areaStyle: {},
                        data: withdrawal_profit
                    },
                    {
                        name: '打赏提成收入', //master_portion
                        type: 'line',
                        stack: '总量',
                        areaStyle: {},
                        data: master_portion
                    },
                    {
                        name: '注册加入收入', //register_profit
                        type: 'line',
                        stack: '总量',
                        areaStyle: {},
                        data: register_profit
                    },
                ]
            };
            this.financialEcharts.setOption(option);
        },
        orderEcharts(date, order_count, order_amount) {
            //初始化Echarts实例
            if (!this.financiaOrderEchart) {
                this.financiaOrderEchart = this.$echarts.init(this.$refs.financialOrderEcharts)
            }
            //绘制图表
            var option = {
                title: {
                    // text: '堆叠区域图'
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
                    data: ['订单数量', '订单金额']
                },
                grid: {
                    left: '1%',
                    right: '6%',
                    bottom: '3%',
                    containLabel: true
                },
                xAxis: [
                    {
                        type: 'category',
                        boundaryGap: false,
                        data: date,
                        axisLabel: {
                            interval: 0, 
                             rotate:-40 
                            },
                    }
                ],
                yAxis: [
                    {
                        type: 'value'
                    }
                ],
                series: [
                    {
                        name: '订单数量', //order_count	
                        type: 'line',
                        stack: '总量',
                        areaStyle: {},
                        data: order_count
                    },
                    {
                        name: '订单金额', //order_amount
                        type: 'line',
                        stack: '总量',
                        areaStyle: {},
                        data: order_amount
                    },
                ]
            };
            this.financiaOrderEchart.setOption(option);
        }
    }
}
