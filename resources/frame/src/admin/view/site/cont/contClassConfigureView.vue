<template>
  <div class="cont-class-box">
    <div class="cont-class-table">
      <el-table
        ref="multipleTable"
        :data="groupsList"
        tooltip-effect="dark"
        style="width: 100%"
        @selection-change="handleSelectionChange"
      >
        <el-table-column width="50">
          <el-checkbox
            slot-scope="scope"
            :id="scope.row.id"
            v-model="scope.row.checkAll"
            :indeterminate="isIndeterminate"
            @change="handleCheckAllChange(scope.row.id)"
          ></el-checkbox>
        </el-table-column>

        <el-table-column label="用户角色" min-width="200">
          <template slot-scope="scope">{{ scope.row.name }}</template>
        </el-table-column>

        <el-table-column label="浏览分类" width="200">
          <el-checkbox slot-scope="scope" v-model="scope.row.viewThreads"></el-checkbox>
        </el-table-column>

        <el-table-column label="发表内容" min-width="200">
          <el-checkbox
            slot-scope="scope"
            v-model="scope.row.createThread"
            :disabled="!scope.row.viewThreads"
          ></el-checkbox>
        </el-table-column>

        <el-table-column label="发表评论" width="200">
          <el-checkbox
            slot-scope="scope"
            v-model="scope.row.replyThread"
            :disabled="!scope.row.viewThreads"
          ></el-checkbox>
        </el-table-column>
      </el-table>
      <Card class="footer-btn">
        <el-button type="primary" :loading="subLoading" size="medium" @click="submitClick">提交</el-button>
      </Card>
    </div>
  </div>
</template>

<script>
import "../../../scss/site/module/contStyle.scss";
import contClassConfigure from "../../../controllers/site/cont/contClassConfigure";
export default {
  name: "cont-class-configure",
  ...contClassConfigure
};
</script>
