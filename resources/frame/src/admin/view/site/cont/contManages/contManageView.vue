<template>
    <div class="cont-manage-box">
      <div class="cont-manage-theme">
        <div class="cont-manage-theme__table">
          <div class="cont-manage-theme__table-header">
            <el-checkbox :indeterminate="isIndeterminate" v-model="checkAll" @change="handleCheckAllChange"></el-checkbox>
            <p class="cont-manage-theme__table-header__title">主题列表</p>
          </div>

          <ContArrange
            v-for="(items,index) in  themeList"
            :author="items.user().username()"
            theme="站长圈"
            :prply="items.postCount()"
            :browse="items.viewCount()"
            last="奶罩"
            :finalPost="formatDate(items.createdAt())"
            :key="index"
          >
            <div class="cont-manage-theme__table-side" slot="side">
              <el-checkbox v-model="checkedTheme[index].status" @change="handleCheckedCitiesChange(index,items.id(),checkedTheme[index].status)"></el-checkbox>
            </div>

            <div style="line-height: 20PX;" slot="main">
              {{items.firstPost().content()}}
            </div>

          </ContArrange>

          <div class="cont-manage-theme__table-footer">
            <el-pagination
              @size-change="handleSizeChange"
              @current-change="handleCurrentChange"
              :current-page.sync="currentPag"
              :page-size="10"
              layout="total, prev, pager, next,jumper"
              :total="total">
            </el-pagination>
          </div>

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
            width="250">
            <template slot-scope="scope">
              <el-radio v-model="operatingSelect" :label="scope.row.label" >{{scope.row.name}}</el-radio>
            </template>
          </el-table-column>

          <el-table-column
            label="选项"
            min-width="250">
            <template slot-scope="scope">

              <el-select @change="selectChange" v-if="scope.row.name === '批量移动到分类'" v-model="categoryId" placeholder="选择圈子">
                <el-option
                  v-for="item in categoriesList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id">
                </el-option>
              </el-select>

              <el-radio-group class="cont-manage__option-select" v-if="scope.row.name === '批量置顶'" v-model="toppingRadio">
                <el-radio :label="1">置顶</el-radio>
                <el-radio :label="2">解除置顶</el-radio>
              </el-radio-group>

              <el-radio-group class="cont-manage__option-select" v-if="scope.row.name === '批量设置精华'" v-model="essenceRadio">
                <el-radio :label="1">精华</el-radio>
                <el-radio :label="2">取消精华</el-radio>
              </el-radio-group>

            </template>
          </el-table-column>

        </el-table>

        <Card class="footer-btn">
          <el-button @click="submitClick" type="primary">提交</el-button>
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
