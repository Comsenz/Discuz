/*
*  提现申请
* */

import Card from '../../../../view/site/common/card/card';

export default {
  data:function () {
    return {
      tableData: [{
        serialNumber: '201912127283748',
        operatingUser: '奶罩',
        withdrawalAmount:"+200.00",
        receivingBankAccount:'中国工商银行 周凡 ************0277 江苏徐州市矿大支行',
        applicationTime:'2019-12-12',
        status:'待审核',
        operating:true
      }, {
        serialNumber: '201912127283748',
        operatingUser: '奶罩',
        withdrawalAmount:"+100.00",
        receivingBankAccount:'中国工商银行 周凡 ************0277 江苏徐州市矿大支行',
        applicationTime:'2019-12-12',
        status:'审核不通过，原因该银行账号出现错误，请确认账号状态是否正常',
        operating:false
      }, {
        serialNumber: '201912127283748',
        operatingUser: '奶罩',
        withdrawalAmount:"+200.00",
        receivingBankAccount:'中国工商银行 周凡 ************0277 江苏徐州市矿大支行',
        applicationTime:'2019-12-12',
        status:'打款成功',
        operating:false
      }, {
        serialNumber: '201912127283748',
        operatingUser: '奶罩',
        withdrawalAmount:"+200.00",
        receivingBankAccount:'中国工商银行 周凡 ************0277 江苏徐州市矿大支行',
        applicationTime:'2019-12-12',
        status:'打款成功',
        operating:false
      }],
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
      value1: '',
      options: [{
        value: '选项1',
        label: '黄金糕'
      }, {
        value: '选项2',
        label: '双皮奶'
      }, {
        value: '选项3',
        label: '蚵仔煎'
      }, {
        value: '选项4',
        label: '龙须面'
      }, {
        value: '选项5',
        label: '北京烤鸭'
      }],
      value: ''
    }
  },
  methods:{

  },
  components:{
    Card
  }
}
