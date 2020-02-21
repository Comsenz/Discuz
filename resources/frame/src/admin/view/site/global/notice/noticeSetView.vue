<template>
    <div class="notice-list-box">
      <div class="notice-list-table marT15">
        <el-table
          :data="tableData"
          style="width: 100%">

          <el-table-column
            prop=""
            label="编号"
            min-width="100">
            <template slot-scope="scope">
              <span v-text="getIndex(scope.$index)"> </span>
            </template>
          </el-table-column>

          <el-table-column
            prop="_data.title"
            label="通知类型"
            width="200">
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
            <div v-if="scope.row._data.status == 1">
              <el-button
                size="mini"
                @click="configClick(scope.row.type)">
                配置
              </el-button>

              <el-button
              	@click.native.prevent="noticeSetting(scope.row._data.id,'close')"
                size="mini">
                关闭
              </el-button>
            </div>
			<div v-if="scope.row._data.status == 0">
	            <el-button
	              size="mini"
	              @click.native.prevent="noticeSetting(scope.row._data.id,'open')"
	            >开启 
	            </el-button>
            </div>
          </template>
        </el-table-column>

        </el-table>
        <Page :total="total" :pageSize="pageLimit" :currentPage="pageNum" @current-change="handleCurrentChange" />
      </div>

    </div>
</template>

<script>
import '../../../../scss/site/module/globalStyle.scss';
import noticeSetCon from '../../../../controllers/site/global/notice/noticeSetCon';
export default {
    name: "withdrawal-application-view",
  ...noticeSetCon
}
</script>
