<template>
    <div class="site-set-box">

      <Card header="站点名称：">
        <CardRow description="你的Discuz!Q 站点的名称">
          <el-input placeholder="站点名称"></el-input>
        </CardRow>
      </Card>

      <Card header="站点介绍：">
        <CardRow description="你的Discuz!Q 站点的介绍">
          <el-input
            type="textarea"
            :autosize="{ minRows: 4, maxRows: 4}"
            placeholder="站点介绍">
          </el-input>
        </CardRow>
      </Card>

      <Card header="站点LOGO：">
        <CardRow description="你的Discuz!Q 站点的LOGO">
          <div class="site-set__logo">
            <el-button size="medium">上传</el-button>
            <i></i>
            <el-button type="text">删除</el-button>
          </div>
        </CardRow>
      </Card>

      <Card header="站点模式：">
        <CardRow description="你的Discuz!Q 站点的运行模式">
          <el-radio v-model="radio" label="1">公开模型</el-radio>
          <el-radio v-model="radio" label="2">付费模型</el-radio>
        </CardRow>
      </Card>

      <Card header="加入价格（元）：">
        <CardRow description="付费模式下，付费成为站点默认角色，需支付的金额">
          <el-input placeholder="加入价格"></el-input>
        </CardRow>
      </Card>

      <Card header="到期时间：">
        <CardRow description="付费模式下，付费成为站点默认角色，可维持的时间">
          <el-radio-group text-color="#67C23A" fill="#67C23A" v-model="radio2">
            <el-radio  label="1">无到期时间</el-radio>
            <el-radio  label="2">有到期时间，加入起
              <el-input
                style="height: 36PX;width: 80PX"
                clearable
                placeholder="天数">
              </el-input>
              天后
            </el-radio>
          </el-radio-group>

        </CardRow>
      </Card>

      <Card header="主题打赏金额分成比例：">
        <CardRow description="主题打赏的分成比例设置">
          <div>
            作者
            <el-input></el-input>
          </div>
          <div>
            平台(圈主)
            <el-input></el-input>
          </div>
        </CardRow>
      </Card>

      <Card header="网站备案信息：">
        <CardRow description="你的Discuz!站点的 ICP 备案编号">
          <el-input></el-input>
        </CardRow>
      </Card>

      <Card header="网站备案信息：">
        <CardRow description="你的Discuz!网站的第三方统计代码">
          <el-input></el-input>
        </CardRow>
      </Card>

      <Card header="关闭站点：">
        <CardRow description="暂时将网站关闭，其他人无法访问，但不影响管理员访问">
          <el-radio v-model="radio" label="1">是</el-radio>
          <el-radio v-model="radio" label="2">否</el-radio>
        </CardRow>
      </Card>

      <Card header="关闭提示信息：">
        <CardRow description="站点关闭时出现的提示信息">
          <el-input></el-input>
        </CardRow>
      </Card>


    </div>
</template>

<script>
import '../../../scss/site/pageStyle.scss';
import siteSetCon from '../../../controllers/site/global/siteSetCon';
export default {
    name: "site-set-view",
  ...siteSetCon
}
</script>
