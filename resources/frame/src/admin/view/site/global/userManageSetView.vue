<template>
    <div class="user-manage-set-box">

      <el-table
        ref="multipleTable"
        :data="tableData"
        tooltip-effect="dark"
        style="width: 100%"
        @selection-change="handleSelectionChange"
        >
        <el-table-column
          type="selection"
          width="50"
          :selectable="checkboxT">
        </el-table-column>

        <el-table-column
          label="用户名"
        >
          <template slot-scope="scope">

            <span v-if="scope.row.role === '站长'">{{scope.row.name}}</span>

            <el-input v-else-if="scope.row.name !== '新增'" v-model="scope.row.name" />

            <p v-else>
              <span class="iconfont iconicon_add icon-add"></span>
              新增
            </p>
          </template>

        </el-table-column>

        <el-table-column
          label="角色"
        >
          <template slot-scope="scope" v-if="scope.row.name !== '新增'">

            <span v-if="scope.row.role === '站长'">{{scope.row.role}}</span>

            <el-select v-else v-model="scope.row.role" placeholder="请选择">
              <el-option
                v-for="item in options"
                :key="item.value"
                :label="item.label"
                :value="item.value">
              </el-option>
            </el-select>
          </template>
        </el-table-column>

        <el-table-column
          prop="address"
          align="center"
          label="">
          <template slot-scope="scope" v-if="scope.row.name !== '新增' && scope.row.role !== '站长'">
            <span style="color: #336699;">删除</span>
          </template>
        </el-table-column>

      </el-table>

      <TableContAdd cont="新增"></TableContAdd>

      <Card class="footer-btn">
        <el-button type="primary" size="medium" @click="loginStatus = 'default'">提交</el-button>
        <el-button  size="medium" :disabled="deleteStatus" >删除</el-button>
      </Card>

    </div>
</template>

<script>
import userManageSetCon from '../../../controllers/site/global/userManageSetCon';
import '../../../scss/site/globalStyle.scss';
export default {
    name: "user-manage-set-view",
  ...userManageSetCon
}
</script>
