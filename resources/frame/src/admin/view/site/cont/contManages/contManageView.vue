<template>
    <div class="cont-manage-box">
      <div class="cont-manage-theme">
        <!--<el-table
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
            label="主题列表"
            prop="theme"
            min-width="250">
          </el-table-column>

          &lt;!&ndash;<el-table-column
            label="作者"
            prop="author">
          </el-table-column>

          <el-table-column
            label="回复"
            prop="prply">
          </el-table-column>

          <el-table-column
            label="浏览"
            prop="browse">
          </el-table-column>

          <el-table-column
            label="最后回复"
            min-width="100"
            prop="finalPost">
          </el-table-column>&ndash;&gt;

        </el-table>-->

        <div class="cont-manage-theme__table">
          <div class="cont-manage-theme__table-header">
            <el-checkbox :indeterminate="isIndeterminate" v-model="checkAll" @change="handleCheckAllChange"></el-checkbox>
            <p class="cont-manage-theme__table-header__title">主题列表</p>
          </div>

          <ContArrange
            author="小虫"
            theme="站长圈"
            prply="123"
            browse="456"
            last="奶罩"
            finalPost="2019-1-1 12:00"
          >

            <div class="cont-manage-theme__table-side" slot="side">
              <el-checkbox ></el-checkbox>
            </div>

            <div style="line-height: 20PX;" slot="main">
              主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容
              主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容
              主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容
              主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容
              主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容
              主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容
              主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容
              主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容
              主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容
              主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容
              主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容
              主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容
              主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容
              主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容
              主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容
              主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容
              主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容
              主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容
              主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容
              主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容
              主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容
              主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容
              主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容
              主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容
              主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容
              主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容
              主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容
              主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容
              主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容
              主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容
              主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容
              主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容
              主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容
              主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容主题内容
            </div>
          </ContArrange>

        </div>


      </div>

      <div class="cont-manage-operating">
        <p>操作</p>
        <el-table
          :data="operatingList"
          tooltip-effect="dark"
          style="width: 100%"
        >
          <el-table-column
            label-class-name="cont-manage-operating__table-label"
            label="操作"
            prop="theme"
            min-width="250">
            <template slot-scope="scope">
              <el-radio v-model="radio" label="1">{{scope.row.name}}</el-radio>
            </template>
          </el-table-column>

          <el-table-column
            label="选项"
            prop="theme"
            min-width="250">
            <template slot-scope="scope">

              <el-select v-if="scope.row.name === '批量移动到分类'" v-model="value" placeholder="选择圈子">
                <el-option
                  v-for="item in options"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value">
                </el-option>
              </el-select>

              <el-radio-group v-if="scope.row.name === '批量置顶'" v-model="toppingRadio">
                <el-radio :label="1">置顶</el-radio>
                <el-radio :label="2">解除置顶</el-radio>
              </el-radio-group>

              <el-radio-group v-if="scope.row.name === '批量设置精华'" v-model="essenceRadio">
                <el-radio :label="1">精华</el-radio>
                <el-radio :label="2">取消精华</el-radio>
              </el-radio-group>

            </template>
          </el-table-column>

        </el-table>

        <Card>
          <el-button type="primary">提交</el-button>
        </Card>

      </div>
    </div>
</template>

<script>
import '../../../../scss/site/contStyle.scss';
import contManageCon from '../../../../controllers/site/cont/contManage/contManageCon'
export default {
    name: "cont-manage-view",
  ...contManageCon
}
</script>
