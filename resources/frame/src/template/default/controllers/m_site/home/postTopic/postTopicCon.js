// import weui from 'weui';


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
      console.log(123);

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
        },
        onConfirm: function (result) {
          console.log(result);
        },
        title: '单列选择器'
      });


    }
  }
}
