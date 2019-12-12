<template>                
    <div class="content-filter-set-box">
      <div class="content-filter-set__search">
        <Card>
          <el-input  size="medium" class="el-cascader__search-input" v-model="serachVal" clearable placeholder="搜索过滤词"></el-input>
          <el-button size="medium" class="content-filter-set__search-button"  @click="onSearch" >搜索</el-button>
        </Card>
      </div>

      <main class="content-filter-set-main">
        <p class="list-set-box">
          <span  @click="$router.push({path:'/admin/add-sensitive-words'})" >批量添加</span>
          <a href="https://2020.comsenz-service.com/api/stop-words/export">导出过滤词库</a>
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
                {{ !scope.row._data.addInputFlag ? scope.row._data.find : ''}}
                <el-input splaceholder="请输入过滤词" clearable v-model="scope.row._data.find" v-show="scope.row._data.addInputFlag"> 
                </el-input> 
              </template>

            </el-table-column>

            <el-table-column
              label="主题和回复处理方式"
            >
              <template slot-scope="scope">
                <el-select v-model="scope.row._data.username" placeholder="请选择" @change="selectChange(scope)">
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
              <template slot-scope="scope">
                <el-select v-model="scope.row._data.ugc" placeholder="请选择" @change="selectChange(scope)">
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
              label="过滤词替换">
              <template slot-scope="scope">
                <el-input v-model="scope.row._data.inputVal" placeholder="请输入替换内容" :disabled="scope.row._data.ugc !== '{REPLACE}' && scope.row._data.username !== '{REPLACE}'" clearable v-show="replace"></el-input>   
              </template>
            </el-table-column>

          </el-table>


          
        <TableContAdd @tableContAddClick="tableContAdd" cont="新增"></TableContAdd>

          <!--<div class="content-filter-set-table-add">
            <p>
              <span class="iconfont iconicon_add icon-add"></span>
              新增
            </p>
          </div>-->

        </div>

        <Card class="footer-btn">
          <el-button type="primary" size="medium" @click="loginStatus">提交</el-button>
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
