<template>
    <div class="page-box">
      <el-pagination
        @size-change="handleSizeChange"
        @current-change="handleCurrentChange"
        :current-page.sync="currentPags"
        :page-size="pageSize"
        layout="total, prev, pager, next,jumper"
        :total="total">
      </el-pagination>
    </div>
</template>

<script>
import '../../../../scss/site/common/page/page.scss';
import pageCon from '../../../../controllers/site/common/page/pageCon';
export default {
    name: "page",
  ...pageCon
}
</script>
