<template>
    <div class="worth-mention-box">
      <div class="worth-mention__default" >
        <el-table
          :data="settingStatus"
          style="width: 100%">
          <el-table-column
            prop="date"
            label="微信设置类型"
          >
            <template slot-scope="scope">
              <i class="iconfont table-icon" :class="scope.row.icon"></i>
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
                @click.native.prevent="loginSetting(scope.$index,scope.row.type,'1')"
              >开启</el-button>

            </template>
          </el-table-column>
        </el-table>
      </div>

     <!-- <div class="worth-mention__h5" v-if="loginStatus === 'h5'">
        <Card header="H5微信授权登录"></Card>

        <Card header="APPID：">
          <CardRow description="填写申请H5微信授权登录后，你获得的APPID">
            <el-input></el-input>

            <template #tail>
              <span  style="color: #336699;margin-left: 15PX" >未申请？点此申请</span>
            </template>
          </CardRow>
        </Card>


        <Card header="App secret：">
          <CardRow description="填写申请H5微信授权登陆后，你获得的App secret">
            <el-input></el-input>
          </CardRow>
        </Card>

        <Card >
          <el-button type="primary" size="medium" @click="loginStatus = 'default'" >提交</el-button>
        </Card>
      </div>

      <div class="worth-mention__applets" v-if="loginStatus === 'applets'">
        <Card header="小程序微信授权登录"></Card>

        <Card header="APPID：">
          <CardRow description="填写申请小程序微信授权登陆后，你获得的APPID">
            <el-input></el-input>
            <template #tail>
              <span style="color: #336699">未申请？点此申请</span>
            </template>
          </CardRow>
        </Card>

        <Card header="App secret：">
          <CardRow description="填写申请小程序微信授权登陆后，你获得的App secret">
            <el-input></el-input>
          </CardRow>
        </Card>

        <Card >
          <el-button type="primary" size="medium" @click@click="loginStatus = 'default'">提交</el-button>
        </Card>
      </div>

      <div v-if="loginStatus === 'pc'">
        <Card header="PC端微信授权登录"></Card>

        <Card header="APPID：">
          <CardRow description="填写申请PC端微信授权登陆后，你获得的APPID">
            <el-input></el-input>
            <template #tail>
              <span style="color: #336699">未申请？点此申请</span>
            </template>
          </CardRow>
        </Card>

        <Card header="App secret：">
          <CardRow description="填写申请PC端微信授权登陆后，你获得的App secret">
            <el-input></el-input>
          </CardRow>
        </Card>

        <Card >
          <el-button type="primary" size="medium" @click="loginStatus = 'default'">提交</el-button>
        </Card>
      </div>-->

    </div>
</template>

<script>
import worthMentioningSetCon from '../../../../controllers/site/global/worthMentioningSet/worthMentioningSetCon';
import '../../../../scss/module/site/globalStyle.scss';
export default {
    name: "worth-mentioning-set-view",
  ...worthMentioningSetCon
}
</script>
