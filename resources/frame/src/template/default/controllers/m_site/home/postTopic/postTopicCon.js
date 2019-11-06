/**
 * 发布主题控制器
 */

export default {
  data:function () {
    return {
      headerTitle:"发布主题",
      selectSort:'选择分类',
      showPopup:false,
      columns: ['杭州', '宁波', '温州', '嘉兴', '湖州']
    }
  },

  methods: {
    backClick() {
      this.$router.go(-1);
    },
    dClick() {
      this.showPopup = true;
    },
    onConfirm(value, index) {
      this.showPopup = false;
      this.selectSort = value;
      // Toast(`当前值：${value}, 当前索引：${index}`);
    },
    onCancel() {
      this.showPopup = false;
    }

  }
}
