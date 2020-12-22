import { isVNode, Fragment, Comment, Text} from 'vue';

const getScopedSlots = ele => {
  return (ele.data && ele.data.scopedSlots) || {};
};

const getSlots = ele => {
  let componentOptions = ele.componentOptions || {};
  if (ele.$vnode) {
    componentOptions = ele.$vnode.componentOptions || {};
  }
  const children = ele.children || componentOptions.children || [];
  const slots = {};
  children.forEach(child => {
    if (!isEmptyElement(child)) {
      const name = (child.data && child.data.slot) || 'default';
      slots[name] = slots[name] || [];
      slots[name].push(child);
    }
  });
  return { ...slots, ...getScopedSlots(ele) };
};
const flattenChildren = (children = [], filterEmpty = true) => {
    const temp = Array.isArray(children) ? children : [children];
    const res = [];
    temp.forEach(child => {
      if (Array.isArray(child)) {
        res.push(...flattenChildren(child, filterEmpty));
      } else if (child && child.type === Fragment) {
        res.push(...flattenChildren(child.children, filterEmpty));
      } else if (child && isVNode(child)) {
        if (filterEmpty && !isEmptyElement(child)) {
          res.push(child);
        } else if (!filterEmpty) {
          res.push(child);
        }
      } else if (isValid(child)) {
        res.push(child);
      }
    });
    return res;
  };
  
const getSlot = (self, name = 'default', options = {}) => {
    if (isVNode(self)) {
      if (self.type === Fragment) {
        return name === 'default' ? flattenChildren(self.children) : [];
      } else if (self.children && self.children[name]) {
        return flattenChildren(self.children[name](options));
      } else {
        return [];
      }
    } else {
      let res = self.$slots[name] && self.$slots[name](options);
      return flattenChildren(res);
    }
  };

export function isEmptyElement(c) {
    return (
        c.type === Comment ||
        (c.type === Fragment && c.children.length === 0) ||
        (c.type === Text && c.children.trim() === '')
    );
}

export {getScopedSlots, getSlots};