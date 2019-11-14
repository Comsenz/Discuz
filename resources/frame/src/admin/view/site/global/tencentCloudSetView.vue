<template>
  <div>
    <div v-if="loginStatus ==='default'" style="padding-top: 15PX">
      <el-table
        :data="tableData"
        style="width: 100%">
        <el-table-column
          prop="date"
          label="腾讯云设置"
        >
          <template slot-scope="scope">
            <i class="iconfont table-icon" :class="scope.row.icon" ></i>
            <div class="table-con-box">
              <p>{{scope.row.name }}</p>
              <p>{{scope.row.description }}</p>
            </div>
          </template>
        </el-table-column>
        <el-table-column
          prop="name"
          label="状态"
          width="100"
        >
          <template slot-scope="scope">
            <p v-if="scope.row.status" style="color: #336699;font-weight: 600;">√</p>
            <p v-else style="color: #336699;font-weight: 600;">—</p>
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
                @click="configClick(scope.row.type)"
              >配置</el-button>
              <el-button
                size="mini"
              >关闭</el-button>
            </div>

            <el-button
              v-else
              size="mini"
            >开启</el-button>

          </template>
        </el-table-column>
      </el-table>
    </div>

    <div v-if="loginStatus === 'yun'">
      <Card header="云api配置"></Card>

      <Card header="APPID：">
        <CardRow description="腾讯云账户 - 访问管理 - 访问密钥 - API密钥的appid。若使用子账号，权限需覆盖所使用
的服务">
          <el-input></el-input>
        </CardRow>
      </Card>

      <Card header="Secretid：">
        <CardRow description="腾讯云账户 - 访问管理 - 访问密钥 - API密钥的SecretId">
          <el-input></el-input>
        </CardRow>
      </Card>

      <Card header="SecretKey：">
        <CardRow description="腾讯云账户 - 访问管理 - 访问密钥 - API密钥的SecretKey">
          <el-input></el-input>
        </CardRow>
      </Card>

      <Card >
        <el-button type="primary" size="medium" @click="loginStatus = 'default'" >提交</el-button>
      </Card>

    </div>

    <div v-if="loginStatus ==='dx'">
      <Card header="短信配置"></Card>

      <Card header="短信验证码使用模板ID：">
        <CardRow description="填写在腾讯云已配置并审核通过的短信验证码的模板的ID">
          <el-input></el-input>
          <template #tail>
            <span  style="color: #336699;margin-left: 15PX" >未申请？点此申请</span>
          </template>
        </CardRow>
      </Card>

      <Card header="短信签名：">
        <CardRow description="腾讯云账户 - 访问管理 - 访问密钥 - API密钥的SecretId">
          <el-input></el-input>
        </CardRow>
      </Card>

      <Card >
        <el-button type="primary" size="medium" @click="loginStatus = 'default'" >提交</el-button>
      </Card>

    </div>

  </div>
</template>

<script>
import tencentCloudSetCon from '../../../controllers/site/global/tencentCloudSetCon';
import '../../../scss/site/pageStyle.scss';
export default {
   name: "tencent-cloud-set-view",
  ...tencentCloudSetCon
}
</script>
