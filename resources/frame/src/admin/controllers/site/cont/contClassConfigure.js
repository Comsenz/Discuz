import Card from "../../../view/site/common/card/card";
import TableContAdd from "../../../view/site/common/table/tableContAdd";

export default {
  data: function() {
    return {
      groupsList: [], //分类列表
      browseCategories: true, // 浏览分类
      content: true, // 发表内容
      comment: true, // 发表评论
      categoriesListLength: "", //分类列表长度
      createCategoriesStatus: false, //添加分类状态
      deleteStatus: true,
      multipleSelection: [], //分类多选列表
      visible: false,
      delLoading: false, //删除按钮状态
      subLoading: false //提交按钮状态
    };
  },

  created() {
    this.getGroups();
    this.query = this.$route.query;
  },

  methods: {
    getGroups() {
      this.appFetch({
        url: "groups",
        method: "get",
        data: {
          include: ["permission"]
        }
      }).then(res => {
          if (res.errors) {
            this.$message.error(res.errors[0].code);
          } else {
            const categoryId = this.query.id;
            this.groupsList = [];
            res.readdata.forEach((item, index) => {
              // 该用户组的所有权限数组
              const allPermissions = [];
              item.permission.map(value => {
                  allPermissions.push(value._data.permission);
              });

              const viewThreads = allPermissions.indexOf(`category${categoryId}.viewThreads`) !== -1;
              const createThread = allPermissions.indexOf(`category${categoryId}.createThread`) !== -1
              const replyThread = allPermissions.indexOf(`category${categoryId}.replyThread`) !== -1;
              const checkAll = viewThreads && createThread && replyThread;
              const isIndeterminate = !checkAll;

              this.groupsList.push({
                id: item._data.id,
                name: item._data.name,
                viewThreads: viewThreads,
                createThread: createThread,
                replyThread: replyThread,
                checkAll: checkAll,
                isIndeterminate: isIndeterminate
                // viewThreads: allPermissions.indexOf(`category${categoryId}.viewThreads`) !== -1,
                // createThread: allPermissions.indexOf(`category${categoryId}.createThread`) !== -1,
                // replyThread: allPermissions.indexOf(`category${categoryId}.replyThread`) !== -1
              });
            });
            console.log(this.groupsList);
          }
        }).catch(err => {});
    },
    handleCheckAllChange(e, id) {
      console.log(e, id);
      // this.checkedCities = val ? cityOptions : [];
      // this.isIndeterminate = false;
    },
  },

  components: {
    Card,
    TableContAdd
  }
};
