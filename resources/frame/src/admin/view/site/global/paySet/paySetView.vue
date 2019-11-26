<template>
  <div class="pay-set-box">
    <div class="pay-set__default">
      <el-table
        :data="tableData"
        style="width: 100%">
        <el-table-column
          prop="date"
          label="支付类型"
        >
          <template slot-scope="scope">
            <i class="iconfont iconweixin table-icon"></i>
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
                size="mini"
                @click="configClick"
              >配置</el-button>
              <el-button
                size="mini"
              >关闭</el-button>
            </div>

            <el-button
              v-else
              size="mini"
              type="primary"
              plain
            >开启</el-button>

          </template>
        </el-table-column>
      </el-table>
    </div>

    <!--<div v-if="loginStatus === 'wx'">
      <Card header="微信支付配置"></Card>

      <Card header="APPID：">
        <CardRow description="appid是微信公众账号或开放平台APP的唯一标识">
          <el-input></el-input>
        </CardRow>
      </Card>

      <Card header="微信支付的商户号（mch_id）：">
        <CardRow description="商户申请微信支付后，由微信支付分配的商户收款账号">
          <el-input></el-input>
        </CardRow>
      </Card>

      <Card header="API密匙（key）：">
        <CardRow description="交易过程生成签名的密钥">
          <el-input></el-input>
        </CardRow>
      </Card>

      <Card header="App Secret：">
        <CardRow description="App Secret是APPID对应的接口密码，用于获取接口调用凭证access_token时使用">
          <el-input></el-input>
        </CardRow>
      </Card>

      <Card >
        <el-button type="primary" size="medium" @click="loginStatus = 'default'" >提交</el-button>
      </Card>

    </div>-->
  </div>
</template>

<script>
import paySetCon from '../../../../controllers/site/global/paySet/paySetCon';
import '../../../../scss/site/globalStyle.scss';
export default {
    name: "pay-set-view",
  ...paySetCon
}
</script>
