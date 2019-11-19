<template>
    <div class="cont-class-box">
      <div class="cont-class-table">
        <el-table
          ref="multipleTable"
          :data="tableData"
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
              <el-input  v-model="scope.row.className" />
            </template>
          </el-table-column>

          <el-table-column
            label="排序"
            width="100"
          >
            <template slot-scope="scope">
              <el-input  v-model="scope.row.sort" />
            </template>
          </el-table-column>

          <el-table-column
            label="分类介绍"
            min-width="250"
          >
            <template slot-scope="scope">
              <el-input  v-model="scope.row.classIntroduction" />
            </template>
          </el-table-column>

          <el-table-column
            label="操作"
            width="100">
            <template slot-scope="scope">
              <el-button type="text">删除</el-button>
            </template>
          </el-table-column>

        </el-table>

        <div class="cont-class-table-add">
          <p>
            <span class="iconfont iconicon_add icon-add"></span>
            <span>添加内容分类</span>
          </p>
        </div>

        <Card >
          <el-button type="primary" size="medium" @click="loginStatus = 'default'">提交</el-button>
          <el-button  size="medium" :disabled="deleteStatus" >删除</el-button>
        </Card>
      </div>
    </div>
</template>

<script>
import '../../../scss/site/contStyle.scss';
import contClassCon from '../../../controllers/site/cont/contClassCon'
export default {
    name: "cont-class",
  ...contClassCon
}
</script>
