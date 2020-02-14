export default {
    data:function(){
        return{
            financialList:[
                {
                    title:"用户总充值",
                    num:"20,187",
                    icon:'iconchongzhi',
                    key:'totalIncome',

                },
                {
                    title:"用户总充提现",
                    num:"20,187",
                    icon:'',
                    key:'totalWithdrawal',

                },
                {
                    title:"用户钱包总金额",
                    num:"20,187",
                    icon:'iconqianbaozongjine',
                    key:'totalWallet',

                },
                {
                    title:"用户订单总数",
                    num:"20,187",
                    icon:'',
                    key:'orderCount',

                },
                {
                    title:"平台总盈利",
                    num:"20,187",
                    icon:'',
                    key:'totalProfit',

                },
                {
                    title:"提现手续费收入",
                    num:"20,187",
                    icon:'',
                    key:'withdrawalProfit',

                },
                {
                    title:"打赏提成收入",
                    num:"20,187",
                    icon:'icondashangtichengshouru',
                    key:'orderRoyalty',

                },
                {
                    title:"注册加入收入",
                    num:"20,187",
                    icon:'iconzhucejiarushouru',
                    key:'totalRegisterProfit',

                },
                
            ],
            financialEcharts:null,
            financiaOrderEchart:null,
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
            financialTime:['',''],   //申请时间
            financialValue1: '', //选择时间周
            financialValue2: '', //选择时间月
            orderValue1:'',
            orderValue2:'',
           
        }
    },
    created(){
        this.statistic() //获取资金概况
 
    },
    mounted(){
        this.earningsStatistics(); //盈利统计
        this.orderStatistics();//订单统计
    },
    methods:{
        statistic(){
            this.appFetch({
                url:'statistic',
                method:'get',
                data:{

                }
            }).then(res=>{
                console.log(res)
                // this.financialList = res.readdata._data;
                var oArr = Object.entries(res.readdata._data);
                for(var i = 0;i < this.financialList.length;i ++){
                    for(var j = 0;j < oArr.length;j ++){
                        if(this.financialList[i].key == oArr[j][0]){
                            this.financialList[i].num = oArr[j][1];
                        }
                    }
                   }
            })
        },
        change(){
            if (this.financialTime == null){
              this.financialTime = ['','']
            } else if(this.financialTime[0] !== '' && this.financialTime[1] !== ''){
              this.financialTime[0] = this.financialTime[0] + '-00-00-00';
              this.financialTime[1] = this.financialTime[1] + '-24-00-00';
            }
            // this.currentPaga = 1;
            this.earningsStatistics();
          },
        earningsStatistics(){  //数据请求传给图标
            this.appFetch({
                url:'statisticChart',
                method:'get',
                data:{
                    'filter[createdAtBegin]':this.financialTime[0],
                    'filter[createdAtEnd]':this.financialTime[1],
                }
            }).then(res=>{
                console.log(res,'盈利数据图标')
                var date = [];
                var total_profit = [];
                var withdrawal_profit = [];
                var master_portion =[];
                var register_profit = [];
                res.readdata.map(item=>{ 
                    date.push(item._data.date)
                    total_profit.push(item._data.total_profit)
                    withdrawal_profit.push(item._data.withdrawal_profit)
                    master_portion.push(item._data.master_portion)
                    register_profit.push(item._data.register_profit)
                    // console.log(this.date,'000000')
                })
                this.earningsEcharts(date,total_profit,withdrawal_profit,master_portion,register_profit)
                
            })
        },
        orderStatistics(){  //订单数据请求
            this.appFetch({
                url:'statisticChart',
                method:'get',
                data:{

                }
            }).then(res=>{
                var date = [];
                var order_count = [];
                var order_amount = [];
                res.readdata.map(item=>{
                    date.push(item._data.date);
                    order_count.push(item._data.order_count);
                    order_amount.push(item._data.order_amount);

                })
                this.orderEcharts(date,order_count,order_amount)
            })
            
        },
        earningsEcharts(date,total_profit,withdrawal_profit,master_portion,register_profit){
            //初始化Echarts实例
            if(!this.financialEcharts){
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
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                },
                xAxis: [
                    {
                        type: 'category',
                        boundaryGap: false,
                        data: date
                    }
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
        orderEcharts(date,order_count,order_amount){
        //初始化Echarts实例
        if(!this.financiaOrderEchart){
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
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis: [
                {
                    type: 'category',
                    boundaryGap: false,
                    data: date
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
