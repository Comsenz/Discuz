
import Card from '../../../view/site/common/card/card';

const cityOptions = ['站点信息', '注册与登录', '附件设置', '支付设置','后台用户管理','后台角色管理'];
const userOptions = ['用户管理','站点信息','角色管理'];

export default {
  data:function () {
    return {
      tableData: [{
        name: '张三',
        method: '处理',
        address: '上海市普陀区金沙江路 1518 弄',
        value:'不处理'
      }],

      checkAll: false,
      checkedCities: ['站点信息', '注册与登录'],
      cities: cityOptions,

      checkedUser:['用户管理'],
      users:userOptions,

      isIndeterminate: true,
      roleStatus:'default'  //default roleEditing

    }
  },
  methods:{
    handleSelectionChange(val) {
      this.multipleSelection = val;

      console.log(this.multipleSelection);
    },

    handleCheckAllChange(val) {
      this.checkedCities = val ? cityOptions : [];
      this.isIndeterminate = false;
    },
    handleCheckedCitiesChange(value) {
      let checkedCount = value.length;
      this.checkAll = checkedCount === this.cities.length;
      this.isIndeterminate = checkedCount > 0 && checkedCount < this.cities.length;
    }

  },
  components:{
    Card
  }
}
