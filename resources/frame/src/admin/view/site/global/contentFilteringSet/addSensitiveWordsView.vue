<template>
  <div class="add-sensitive-words-box">
    <Card header="批量添加本地敏感词：">
      <el-input
        type="textarea"
        :autosize="{ minRows: 5, maxRows: 5}"
        v-model="textarea"
        placeholder="敏感词">
      </el-input>

      <el-radio-group text-color="#67C23A" fill="#67C23A" v-model="radio">
        <div>
          <el-radio :label=false>不导入已经存在的词语</el-radio>
        </div>
        <div>
          <el-radio :label=true>使用新的设置覆盖已经存在的词语</el-radio>
        </div>
      </el-radio-group>

    </Card>

    <Card >
      <el-button type="primary" size="medium" @click="loginStatus">提交</el-button>
    </Card>

    <Card>
      <h2>提示：</h2>
      <p>批量添加内容格式：</p>
      <p>目前标识</p>
      <p>敏感词内容=主题和回复处理方式标识|用户名处理方式标识。</p>
      <p>主题的处理方式标识为：不处理，审核，禁止，替换词；</p>
      <p>用户名的处理方式标识为 不处理，禁止。</p>
      <p>如不处理直接留空即可；如需当用户发布包含某个词语的文字时，自动标记为需要人工审核，而不直接显示或替换过滤，请将其对应的替换内容设置为{MOD}即可；如需禁止发布包含某个词语的文字，而不是替换过滤，请将其对应的替换内容设置为{BANNED}即可；如需替换则直接填写替换词即可；如不填写处理方式则自动替换为**。</p>
      <p>举例：</p>
      <p>敏感词一号={MOD}|{BANNED}</p>
      <p>敏感词二号={BANNED}</p>
      <p>敏感词三号=替换词</p>
      <p>敏感词四号</p>
      <p>说明：</p>
      <p>主题和回复处理方式为审核，用户名处理方式为禁止</p>
      <p>主题和回复处理方式为禁止，用户名不处理</p>
      <p>主题和回复处理方式为替换，用户名不处理</p>
      <p>主题和回复处理方式为替换成**，用户名不处理</p>
    </Card>

  </div>
</template>

<script>
import '../../../../scss/site/module/globalStyle.scss';
import addSensitiveWordsCon from '../../../../controllers/site/global/contentFilteringSet/addSensitiveWordsCon';
export default {
    name: "add-sensitive-words-view",
  ...addSensitiveWordsCon
}
</script>
