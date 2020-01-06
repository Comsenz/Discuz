<template>
    <div class="user-rol-box">
      <div class="user-rol-table">
        <el-table
          :data="tableData"
          style="width: 100%"
          @selection-change="handleSelectionChange">
          <el-table-column
            type="selection"
            width="55"
            :selectable="checkSelectable">
          </el-table-column>

          <el-table-column
            label="级别名称">
            <template slot-scope="scope">
              <el-input v-model="scope.row._data.name"></el-input>
            </template>
          </el-table-column>

          <!--<el-table-column
            label="排序">
            <template slot-scope="scope">
              <el-input></el-input>
            </template>
          </el-table-column>-->

          <el-table-column>
            <template slot-scope="scope">
              <el-button v-if="scope.row._data.id !== '1'" type="text" @click="$router.push({path:'/admin/rol-permission',query:{id:scope.row._data.id,name:scope.row._data.name}})">设置权限</el-button>
              <el-button v-if="scope.row._data.id !== '1' && scope.row._data.id !== '6' && scope.row._data.id !== '7' && scope.row._data.id !== '10'" @click="singleDelete(scope.$index,scope.row._data.id)" type="text">删除</el-button>
            </template>
          </el-table-column>

          <el-table-column
            min-width="115">
            <template slot-scope="scope">
              <el-radio v-model="radio" @change="radioChange(scope.row)" v-if="scope.row._data.id != 1 && scope.row._data.id !== '6' && scope.row._data.id !== '7'" :label="scope.row._data.id">设为加入站点的默认级别</el-radio>
            </template>
          </el-table-column>

        </el-table>
      </div>

      <TableContAdd cont="新增" @tableContAddClick="addList"></TableContAdd>

      <Card class="footer-btn">
        <el-button type="primary" size="medium" @click="submitClick" >提交</el-button>
        <el-button  size="medium" :disabled="deleteStatus" @click="deleteClick" >删除</el-button>
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
