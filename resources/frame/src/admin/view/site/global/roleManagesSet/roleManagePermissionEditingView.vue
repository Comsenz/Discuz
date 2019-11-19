<template>
    <div class="role-editing-box">
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
        <el-button type="primary" size="medium" @click="$router.push({path:'/admin/role-manage-set'})">提交</el-button>
      </Card>
    </div>
</template>

<script>
import '../../../../scss/site/global.scss';
import roleManagePermissionEditingCon from '../../../../controllers/site/global/roleManageSet/roleManagePermissionEdititingCon';
export default {
    name: "role-manage-permission-editing-view",
  ...roleManagePermissionEditingCon
}
</script>
