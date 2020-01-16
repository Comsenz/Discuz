<template>
    <div class="cont-class-box">
      <div class="cont-class-table">
        <el-table
          ref="multipleTable"
          :data="categoriesList"
          tooltip-effect="dark"
          style="width: 100%"
          @selection-change="handleSelectionChange"
        >
          <el-table-column
            type="selection"
            width="50">
          </el-table-column>

          <el-table-column
            label="分类名称"
            min-width="200">
            <template slot-scope="scope">
              <el-input clearable v-model="scope.row.name" />
            </template>
          </el-table-column>

          <el-table-column
            label="排序"
            width="120"
          >
            <template slot-scope="scope">
              <el-input clearable v-model="scope.row.sort" />
            </template>
          </el-table-column>

          <el-table-column
            label="分类介绍"
            min-width="250"
          >
            <template slot-scope="scope">
              <el-input clearable  v-model="scope.row.description" />
            </template>
          </el-table-column>

          <el-table-column
            label="操作"
            width="100">
            <template slot-scope="scope">

              <el-popover
                width="100"
                placement="top"
                :ref="`popover-${scope.$index}`">
                <p>确定删除该项吗？</p>
                <div style="text-align: right; margin: 10PX 0 0 0 ">
                  <el-button type="text" size="mini" @click="scope._self.$refs[`popover-${scope.$index}`].doClose()">
                    取消
                  </el-button>
                  <el-button type="danger" size="mini" @click="deleteClick(scope.row.id,scope.$index);scope._self.$refs[`popover-${scope.$index}`].doClose()" >确定</el-button>
                </div>
                <el-button type="text" slot="reference">删除</el-button>
              </el-popover>

              <!--<el-button type="text" @click="deleteClick(scope.row.id)">删除</el-button>-->
            </template>
          </el-table-column>

        </el-table>

        <TableContAdd @tableContAddClick="tableContAdd" cont="添加内容分类"></TableContAdd>

        <Card class="footer-btn" >
          <el-button type="primary" size="medium" @click="submitClick" >提交</el-button>

          <el-popover
            width="100"
            placement="top"
            v-model="visible">
            <p>确定删除该项吗？</p>
            <div style="text-align: right; margin: 10PX 0 0 0 ">
              <el-button type="text" size="mini" @click="visible = false">取消</el-button>
              <el-button type="danger" size="mini"  @click="deleteAllClick" >确定</el-button>
            </div>
            <el-button size="medium" style="margin-left: 10PX" :disabled="deleteStatus"  slot="reference">删除</el-button>
          </el-popover>

          <!--<el-button  size="medium" :disabled="deleteStatus" @click="deleteAllClick" >删除</el-button>-->
        </Card>
      </div>
    </div>
</template>

<script>
import '../../../scss/module/site/contStyle.scss';
import contClassCon from '../../../controllers/site/cont/contClassCon'
export default {
    name: "cont-class",
  ...contClassCon
}
</script>
