<template>
    <div class="role-manage-set-box">
      <div v-if="roleStatus === 'default'">
        <el-table
          ref="multipleTable"
          :data="tableData"
          tooltip-effect="dark"
          style="width: 100%"
          @selection-change="handleSelectionChange">
          <el-table-column
            type="selection"
            width="50">
          </el-table-column>

          <el-table-column
            label="角色名称">
            <template slot-scope="scope">

              <el-input v-model="scope.row.name" style="width: 60%" />

            </template>

          </el-table-column>

          <el-table-column
            label="角色"
          >
            <template slot-scope="scope">
              <el-button type="text">权限编辑</el-button>
              <el-button type="text">删除</el-button>
            </template>
          </el-table-column>

        </el-table>

        <div class="role-manage-set-table-add">
          <p>
            <span class="iconfont iconicon_add icon-add"></span>
            <span>新增</span>
          </p>
        </div>

        <Card>
          <el-button type="primary" si ze="medium" @click="roleStatus = 'roleEditing'">提交</el-button>
          <el-button  size="medium" :disabled="deleteStatus" >删除</el-button>
        </Card>

      </div>

      <div class="role-editing-box"  v-if="roleStatus === 'roleEditing'">

        <Card header="角色一的权限编辑">

          <table class="role-table">
            <tr>
              <th>
                <el-checkbox :indeterminate="isIndeterminate" v-model="checkAll" @change="handleCheckAllChange">全选</el-checkbox>
              </th>
            </tr>
            <tr>
              <td>
                <el-checkbox-group v-model="checkedCities" @change="handleCheckedCitiesChange">
                  <el-checkbox v-for="city in cities" :label="city" :key="city">{{city}}</el-checkbox>
                </el-checkbox-group>
              </td>
            </tr>
          </table>

          <table class="role-table">
            <tr>
              <th>
                <el-checkbox :indeterminate="isIndeterminate" v-model="checkAll" @change="handleCheckAllChange">用户</el-checkbox>
              </th>
            </tr>
            <tr>
              <td>
                <el-checkbox-group v-model="checkedUser" @change="handleCheckedCitiesChange">
                  <el-checkbox v-for="user in users" :label="user" :key="user">{{user}}</el-checkbox>
                </el-checkbox-group>
              </td>
            </tr>
          </table>

        </Card>

        <Card>
          <el-button type="primary" size="medium" @click="roleStatus = 'default'">提交</el-button>
        </Card>

      </div>

    </div>
</template>

<script>
import roleManageSetCon from '../../../controllers/site/global/roleManageSetCon';
import '../../../scss/site/pageStyle.scss';
export default {
    name: "role-manage-set-view",
  ...roleManageSetCon
}
</script>
