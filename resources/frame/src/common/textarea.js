/**
* 文本框根据输入内容自适应高度
* @param       {HTMLElement}   输入框元素
* @param       {Number}        设置光标与输入框保持的距离(默认0)
* @param       {Number}        设置最大高度(可选)
* @callback    {Function}      设置回调函数(可选)
*/
export const autoTextarea = function (elem, extra, maxHeight, callback) {
  extra = extra || 0;
  var isFirefox = !!document.getBoxObjectFor || 'mozInnerScreenX' in window,
    isOpera = !!window.opera && !!window.opera.toString().indexOf('Opera'),
    addEvent = function (type, callback) {
      elem.addEventListener ?
        elem.addEventListener(type, callback, false) :
        elem.attachEvent('on' + type, callback);
    },
    getStyle = elem.currentStyle ? function (name) {
      var val = elem.currentStyle[name];

      if (name === 'height' && val.search(/px/i) !== 1) {
        var rect = elem.getBoundingClientRect();
        return rect.bottom - rect.top -
          parseFloat(getStyle('paddingTop')) -
          parseFloat(getStyle('paddingBottom')) + 'px';
      };

      return val;
    } : function (name) {
      return getComputedStyle(elem, null)[name];
    },
    minHeight = parseFloat(getStyle('height'));

  elem.style.resize = 'none';

  var change = function () {
    var scrollTop, height,
      padding = 0,
      style = elem.style;

    if (elem._length === elem.value.length) return;
    elem._length = elem.value.length;

    if (!isFirefox && !isOpera) {
      padding = parseInt(getStyle('paddingTop')) + parseInt(getStyle('paddingBottom'));
    };
    scrollTop = document.body.scrollTop || document.documentElement.scrollTop;

    elem.style.height = minHeight + 'px';
    if (elem.scrollHeight > minHeight) {
      if (maxHeight && elem.scrollHeight > maxHeight) {
        height = maxHeight - padding;
        // style.overflowY = 'auto';
        style.overflowY = 'hidden';
      } else {
        height = elem.scrollHeight - padding;
        // style.overflowY = 'scroll';
        style.overflowY = 'hidden';
      };
      style.height = height + extra + 'px';
      scrollTop += parseInt(style.height) - elem.currHeight;
      document.body.scrollTop = scrollTop;
      document.documentElement.scrollTop = scrollTop;
      elem.currHeight = parseInt(style.height);

      callback(parseInt(style.height));
    };
  };

  addEvent('propertychange', change);
  addEvent('input', change);
  addEvent('focus', change);
  change();
};

export const debounce = function (func, delay) {
  let timer;

  return function (...args) {
    if (timer) {
      clearTimeout(timer);
    }
    timer = setTimeout(() => {
      func.apply(this, args);
    }, delay || 500);
  }
}
