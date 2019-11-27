<template>
    <div class="user-manage-box">
      <Card header="用户名：">
        <CardRow description="可使用通配符 *，多个用户名用半角逗号 ',' 隔开">
          <el-input></el-input>
        </CardRow>
      </Card>

      <Card header="用户 UID：">
        <CardRow>
          <el-input></el-input>
        </CardRow>
      </Card>

      <Card header="用户角色：">
        <CardRow description="设置允许参与搜索的用户组，可以按住 CTRL 多选">
          <el-select v-model="value" multiple  placeholder="请选择">
            <el-option
              v-for="item in options"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
        </CardRow>
      </Card>

      <el-collapse-transition>
        <div v-show="checked">
          <Card header="手机号：">
            <CardRow>
              <el-input></el-input>
            </CardRow>
          </Card>

          <Card header="是否绑定微信：">
            <el-radio v-model="radio1" label="1">是</el-radio>
            <el-radio v-model="radio1" label="2">否</el-radio>
          </Card>

          <Card header="是否实名认证：">
            <el-radio v-model="radio2" label="1">是</el-radio>
            <el-radio v-model="radio2" label="2">否</el-radio>
          </Card>

          <Card header="实名认证姓名：">
            <CardRow>
              <el-input></el-input>
            </CardRow>
          </Card>

          <Card header="实名认证身份证号：">
            <CardRow>
              <el-input></el-input>
            </CardRow>
          </Card>

        </div>
      </el-collapse-transition>

      <Card>
        <el-button @click="searchBtn" type="primary" size="medium">搜索</el-button>
        <el-checkbox v-model="checked" @change="checkedStatus">更多选项</el-checkbox>
      </Card>

    </div>
</template>

<script>
import '../../../../scss/site/userStyle.scss';
import userManageCon from '../../../../controllers/site/user/userMange/userManageCon';
export default {
    name: "user-manage-view",
  ...userManageCon
}
</script>
