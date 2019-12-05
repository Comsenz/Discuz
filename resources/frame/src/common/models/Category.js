import Model from '../Model';

export default class Category extends Model {}

Object.assign(Category.prototype, {
  name:Model.attribute('name'),
});
