/**
 * 发布主题控制器
 */

export default {
  data:function () {
    return {
      headerTitle:"发布主题",
      selectSort:'选择分类'
    }
  },

  methods: {
    backClick() {
      this.$router.go(-1);
    },
    dClick() {
      var _this = this;

      weui.picker([{
        label: '飞机票',
        value: 0
      }, {
        label: '火车票',
        value: 1
      }, {
        label: '的士票',
        value: 2
      },{
        label: '公交票 (disabled)',
        disabled: true,
        value: 3
      }, {
        label: '其他',
        value: 4
      }], {
        onChange: function (result) {
          console.log(result);
          // let selectName = result[0].label;
          // _this.selectSort = selectName;
        },
        onConfirm: function (result) {    //问题：this.能读取到data数据，但是修改后页面不更新
          console.log(result[0].label);
          let selectName = result[0].label;
          _this.selectSort = selectName;
        },
        title: '选择分类'
      });


    }
  }
}
