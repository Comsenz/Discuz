<template>
    <textarea ref="textarea" v-model="keywords" :maxlength="keywordsMax" @change="searchChange"></textarea>

</template>
<style>
  .textarea{
          width: 400px;
          min-height: 20px;
          max-height: 300px;
          _height: 120px;
          margin-left: auto;
          margin-right: auto;
          padding: 3px;
          outline: 0;
          border: 1px solid #a0b3d6;
          font-size: 12px;
          line-height: 24px;
          padding: 2px;
          word-wrap: break-word;
          overflow-x: hidden;
          overflow-y: auto;

          border-color: rgba(82, 168, 236, 0.8);
          box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1), 0 0 8px rgba(82, 168, 236, 0.6);
      }
</style>
<script>
import { debounce, autoTextarea } from '../../../../../common/textarea.js';

  let rootFontSize = parseFloat(document.documentElement.style.fontSize);

  export default {
    data () {
      return {
        keywordsMax: 1000,
        keywords: '',
        list: []
      }
    },
    mounted () {
        this.$nextTick(() => {
          let textarea = this.$refs.textarea;
          textarea.focus();
          let prevHeight = 300;
          textarea && autoTextarea(textarea, 5, 0, (height) => {
            height += 20;
            if (height !== prevHeight) {
              prevHeight = height;
              let rem = height / rootFontSize;
              // this.$refs.list.style.height = `calc(100% - ${rem}rem)`;
            }
          });
        })
    },
    methods: {
      clearKeywords () {
        this.keywords = '';
        this.list = [];
        let textarea = this.$refs.textarea;
        let height = 40;
        let rem = height / rootFontSize;
        textarea.style.height = `${rem}rem`;
        rem = (height + 20) / rootFontSize;
        // this.$refs.list.style.height = `calc(100% - ${rem}rem)`;
        textarea.focus();
      },
      searchChange: debounce(function () {
        let trim = this.keywords.trim();
        if (!trim) {
          this.list = [];
          return;
        }
        const params = {
          keywords: this.keywords
        }
        // 调api ...
      })
    }
  }
</script>
