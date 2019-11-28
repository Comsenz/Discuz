<template>
  <div class="add-sensitive-words-box">
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

  </div>
</template>

<script>
import '../../../../scss/site/globalStyle.scss';
import addSensitiveWordsCon from '../../../../controllers/site/global/contentFilteringSet/addSensitiveWordsCon';
export default {
    name: "add-sensitive-words-view",
  ...addSensitiveWordsCon
}
</script>
