<template>
    <div class="content-filter-set-box">
      <div class="content-filter-set__search">
        <Card>
          <el-input  size="medium" class="el-cascader__search-input"></el-input>
          <el-button size="medium" class="content-filter-set__search-button">搜索</el-button>
        </Card>
      </div>

      <main class="content-filter-set-main">
        <p class="list-set-box">
          <span  @click="$router.push({path:'/admin/add-sensitive-words'})" >批量添加</span>
          <span>导出过滤词库</span>
        </p>

        <div>
          <el-table
            ref="multipleTable"
            :data="tableData"
            tooltip-effect="dark"
            style="width: 100%"
            @selection-change="handleSelectionChange">
            <el-table-column
              type="selection"
              width="50">
            </el-table-column>

            <el-table-column
              label="过滤词"
            >
              <template slot-scope="scope">
              <span v-if="scope.row.name !== '新增'">
                {{ scope.row.name }}
              </span>
                <span v-else style="color: #336699;">
                +{{ scope.row.name }}
              </span>
              </template>

            </el-table-column>

            <el-table-column
              label="主题和回复处理方式"
            >
              <template slot-scope="scope" v-if="scope.row.name !== '新增'">
                <el-select v-model="scope.row.method" placeholder="请选择">
                  <el-option
                    v-for="item in options"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value">
                  </el-option>
                </el-select>
              </template>
            </el-table-column>

            <el-table-column
              prop="address"
              label="用户名处理方式">
              <template slot-scope="scope" v-if="scope.row.name !== '新增'">
                <el-select v-model="scope.row.value" placeholder="请选择">
                  <el-option
                    v-for="item in options"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value">
                  </el-option>
                </el-select>
              </template>
            </el-table-column>

          </el-table>


          <TableContAdd cont="新增"></TableContAdd>

          <!--<div class="content-filter-set-table-add">
            <p>
              <span class="iconfont iconicon_add icon-add"></span>
              新增
            </p>
          </div>-->

        </div>

        <Card class="footer-btn">
          <el-button type="primary" size="medium" @click="loginStatus = 'default'">提交</el-button>
          <el-button size="medium" :disabled="deleteStatus">删除</el-button>
        </Card>

      </main>

      <!--<div class="batch-set-box" v-if="loginStatus === 'batchSet'">
        <Card header="批量添加本地敏感词：">
          <el-input
            type="textarea"
            :autosize="{ minRows: 5, maxRows: 5}"
            placeholder="敏感词">
          </el-input>

          <el-radio-group text-color="#67C23A" fill="#67C23A" v-model="radio2">
            <div>
              <el-radio  label="1">不导入已经存在的词语</el-radio>
            </div>
            <div>
              <el-radio  label="2">使用新的设置覆盖已经存在的词语</el-radio>
            </div>
          </el-radio-group>

        </Card>

        <Card >
          <el-button type="primary" size="medium" @click="loginStatus = 'default'">提交</el-button>
        </Card>

        <Card>
          <h2>提示：</h2>
          <p>批量添加内容格式：</p>
          <p>敏感词内容|主题和回复处理方式表示|用户名处理方式标识。主题的处理方式标识未 不处理0，禁止1，替换为*2，审核3；</p>
          <p>用户名的处理方式标识为 不处理0，禁止1，举例：</p>
          <p>敏感词一号|1|1</p>
          <p>敏感词二号|2|1</p>
        </Card>

      </div>-->

    </div>
</template>

<script>
import contentFilteringSetCon from '../../../../controllers/site/global/contentFilteringSet/contentFilteringSetCon';
import '../../../../scss/site/globalStyle.scss';
export default {
    name: "content-filtering-set-view",
  ...contentFilteringSetCon
}
</script>
