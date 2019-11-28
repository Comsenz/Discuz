<template>
    <div class="user-search-list-box">
      <Card header="用户搜索结果："></Card>

      <div class="user-search-list__table">

        <div class="user-search-list__table-title">
          <p>共搜索到 4 名符合条件的用户</p>
          <el-button type="text">重新搜索</el-button>
          <el-button type="text">导出用户信息</el-button>
        </div>

        <div class="user-search-list__table-cont">
          <el-table
            :data="tableData"
            style="width: 100%"
            @selection-change="handleSelectionChange">
            <el-table-column
              type="selection"
              width="55">
            </el-table-column>

            <el-table-column
              prop="number"
              label="编号"
              min-width="60"
              >
            </el-table-column>

            <el-table-column
              prop="userName"
              label="用户名"
              min-width="50"
              >
            </el-table-column>

            <el-table-column
              prop="publishTopic"
              label="发布主题">
            </el-table-column>

            <el-table-column
              prop="userGroup"
              label="用户组">
            </el-table-column>

            <el-table-column
              label="">
              <template slot-scope="scope">
                <el-button type="text" @click="$router.push({path:'/admin/user-details'})">详情</el-button>
                <el-button type="text" @click="$router.push({path:'/admin/wallet'})">钱包</el-button>
                <el-button type="text">禁用</el-button>
              </template>
            </el-table-column>

          </el-table>
        </div>

        <Card>
          <el-button type="primary" size="medium" @click="loginStatus = 'default'">删除</el-button>
          <el-button size="medium" :disabled="deleteStatus">禁用</el-button>
        </Card>

      </div>

      <div class="user-search-list__prompt">
        <h1>提示：</h1>
        <p>导出用户信息最多支持 10000 条数据。导出的 .xls 文件可用 EXCEL 打开。</p>
      </div>

    </div>
</template>

<script>
import '../../../../scss/site/userStyle.scss';
import userSearchListCon from '../../../../controllers/site/user/userMange/userSearchListCon'
export default {
    name: "search-results-view",
  ...userSearchListCon
}
</script>
