/*
* 系统通知管理器
* */

import Card from '../../../../view/site/common/card/card';
import CardRow from '../../../../view/site/common/card/cardRow';
import TableContAdd from '../../../../view/site/common/table/tableContAdd';
import Page from '../../../../view/site/common/page/page';

export default {
  data: function () {
    return {
      tableData: [],
      pageNum: 1,
      pageLimit: 20,
      total: 0,
    }
  },
  created() {
    this.getNoticeList();
  },
  methods: {
    getNoticeList() {
      //初始化通知设置列表
      this.appFetch({
        url: 'noticeList',
        method: 'get',
        data: {}
      }).then(res => {
        if (res.errors) {
          this.$message.error(res.errors[0].code);
        } else {
          this.tableData = res.readdata;
          this.total = res.readdata.length;
        }
      }).catch(err => {
      })
    },
    noticeSetting(id, actionName) {      //修改开启状态
      let statusTemp = 1;// 默认开启状态
      if (actionName == 'close') {
        statusTemp = 0;
      } else if (actionName == 'open') {
        statusTemp = 1;
      }
      this.appFetch({
        url: 'notification',
        method: 'patch',
        splice: id,
        data: {
          "data": {
            "attributes": {
              "status": statusTemp,
            }
          }
        }
      }).then(res => {
        if (res.errors) {
          this.$message.error(res.errors[0].code);
        } else {
          this.$message({
            message: '修改成功',
            type: 'success'
          });
          this.getNoticeList();
        }
      })
    },

    //获取表格序号
    getIndex($index) {
      //表格序号
      return (this.pageNum - 1) * this.pageLimit + $index + 1
    },
    handleCurrentChange(val) {
      this.pageNum = val;
      this.getNoticeList();
    },
    configClick(id) {  //点击配置跳到对应的配置页面
      this.$router.push({path:'/admin/notice-configure',query: {id:id,type:'system'}});
    }
  },

  components: {
    Card,
    CardRow,
    Page
  }
}

