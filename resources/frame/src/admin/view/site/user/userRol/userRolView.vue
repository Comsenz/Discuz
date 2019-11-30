<template>
    <div class="user-rol-box">
      <div class="user-rol-table">
        <el-table
          :data="tableData"
          style="width: 100%"
          @selection-change="handleSelectionChange">
          <el-table-column
            type="selection"
            width="55">
          </el-table-column>

          <el-table-column
            label="级别名称">
            <template slot-scope="scope">
              <el-input></el-input>
            </template>
          </el-table-column>

          <el-table-column
            label="排序">
            <template slot-scope="scope">
              <el-input></el-input>
            </template>
          </el-table-column>

          <el-table-column>
            <template slot-scope="scope">
              <el-button type="text" @click="$router.push({path:'/admin/rol-permission'})">设置权限</el-button>
              <el-button type="text">删除</el-button>
            </template>
          </el-table-column>

          <el-table-column
            min-width="115">
            <template slot-scope="scope">
              <el-radio v-model="radio" label="1">设为加入圈子的默认级别</el-radio>
            </template>
          </el-table-column>

        </el-table>
      </div>

      <TableContAdd cont="新增"></TableContAdd>

      <!--<div class="user-rol-table-add">
        <p>
          <span class="iconfont iconicon_add icon-add"></span>
          <span>新增</span>
        </p>
      </div>-->

      <Card class="footer-btn">
        <el-button type="primary" size="medium" >提交</el-button>
        <el-button  size="medium" :disabled="deleteStatus" >删除</el-button>
      </Card>

    </div>
</template>

<script>
import '../../../../scss/site/userStyle.scss';
import userRolCon from '../../../../controllers/site/user/userRol/userRolCon';
export default {
    name: "user-rol-view",
  ...userRolCon
}
</script>
