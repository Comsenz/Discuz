<template>
  <div>
    <div style="padding-top: 15PX">
      <el-table
        :data="tableData"
        style="width: 100%">
        <el-table-column
          prop="date"
          label="腾讯云设置"
        >
          <template slot-scope="scope">
            <div class="pay-set-type-box">
              <i class="iconfont table-icon" :class="scope.row.icon" ></i>
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
            <span v-else class="iconfont iconicon_"  ></span>
          </template>
        </el-table-column>
        <el-table-column
          prop="address"
          label="操作"
          width="180">
          <template slot-scope="scope">
            <div v-if="scope.row.status">
              <el-button
                v-if="scope.row.setFlag && scope.row.type !== 'img' && scope.row.type !=='text'"
                size="mini"
                @click="configClick(scope.row.type)">
                配置
              </el-button>

              <el-button
              @click.native.prevent="loginSetting(scope.$index,scope.row.type,'0')"
                size="mini">
                关闭
              </el-button>

            </div>

            <el-button
              v-else
              size="mini"
              @click.native.prevent="loginSetting(scope.$index,scope.row.type,'1')"
            >开启</el-button>

          </template>
        </el-table-column>
      </el-table>
    </div>
  </div>
</template>

<script>
import tencentCloudSetCon from '../../../../controllers/site/global/tencentCloundSet/tencentCloudSetCon';
import '../../../../scss/site/module/globalStyle.scss';
export default {
   name: "tencent-cloud-set-view",
  ...tencentCloudSetCon
}
</script>
