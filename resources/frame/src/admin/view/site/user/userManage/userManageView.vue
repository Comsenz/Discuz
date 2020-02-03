<template>
    <div class="user-manage-box">
      <Card header="用户名：">
        <CardRow description="可使用通配符 *，多个用户名用半角逗号 ',' 隔开">
          <el-input v-model="username"></el-input>
        </CardRow>
      </Card>

      <Card header="用户 UID：">
        <CardRow>
          <el-input v-model="userUID"></el-input>
        </CardRow>
      </Card>

      <Card header="用户角色：">
        <CardRow description="显示允许设置的用户角色，可多选">
          <el-select v-model="userRole" multiple  placeholder="请选择">
            <el-option
              v-for="item in options"
              :disabled="item.value === '7' "
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
        </CardRow>
      </Card>

      <Card header="用户状态：">
      <CardRow >
        <el-select v-model="userStatus" placeholder="请选择">
          <el-option
            v-for="item in optionsStatus"
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
              <el-input v-model="userPhone"></el-input>
            </CardRow>
          </Card>

          <Card header="是否绑定微信：">
            <el-radio v-model="userWeChat" label="yes">是</el-radio>
            <el-radio v-model="userWeChat" label="no">否</el-radio>
          </Card>

        </div>
      </el-collapse-transition>

      <Card class="footer-btn">
        <el-button @click="searchBtn" type="primary" size="medium">搜索</el-button>
        <el-checkbox v-model="checked" @change="checkedStatus">更多选项</el-checkbox>
      </Card>

    </div>
</template>

<script>
import '../../../../scss/site/module/userStyle.scss';
import userManageCon from '../../../../controllers/site/user/userMange/userManageCon';
export default {
    name: "user-manage-view",
  ...userManageCon
}
</script>
