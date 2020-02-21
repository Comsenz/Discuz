<template>
  <div class="pay-set-box">
    <div class="pay-set__default">
      <el-table
        :data="settingStatus"
        style="width: 100%">
        <el-table-column
          prop="date"
          label="支付类型"
        >
          <template slot-scope="scope">
            <div class="pay-set-type-box">
              <i class="iconfont iconweixin table-icon"></i>
              <div class="table-con-box">
                <p>{{scope.row.name }}</p>
                <p>{{scope.row.description }}</p>
              </div>
            </div>
          </template>
        </el-table-column>
        <el-table-column
          prop="name"
          label="状态"
          width="100"
          align="center"
        >
          <template slot-scope="scope">
            <span v-if="scope.row.status" class="iconfont iconicon_select" ></span>
            <span v-else class="iconfont iconicon_"></span>
          </template>
        </el-table-column>
        <el-table-column
          prop="address"
          label="操作"
          width="180">
          <template slot-scope="scope">
            <div v-if="scope.row.status">
              <el-button
                size="mini"
                @click="configClick(scope.row.tag)"
              >配置</el-button>
              <el-button
                size="mini"
                @click.native.prevent="loginSetting(scope.$index,scope.row.type,'0')"
              >关闭</el-button>
            </div>

            <el-button
              v-else
              size="mini"
              type="primary"
              plain
              @click.native.prevent="loginSetting(scope.$index,scope.row.type,'1')"
            >开启</el-button>

          </template>
        </el-table-column>
      </el-table>
    </div>
  </div>
</template>

<script>
import paySetCon from '../../../../controllers/site/global/paySet/paySetCon';
import '../../../../scss/site/module/globalStyle.scss';
export default {
    name: "pay-set-view",
  ...paySetCon
}
</script>
