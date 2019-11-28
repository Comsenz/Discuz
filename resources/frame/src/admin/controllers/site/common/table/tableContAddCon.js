
/*
* 添加表格空内容
* */

export default {
  data:function () {
    return {

    }
  },

  methods:{
    tableContAddClick(){
      this.$emit('tableContAddClick')
    }
  }
}
