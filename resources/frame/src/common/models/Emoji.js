import Model from '../Model';

export default class Emoji extends Model {}

Object.assign(Emoji.prototype, {
  category: Model.attribute('category'),
  code: Model.attribute('code'),
  id: Model.attribute('id'),
  order: Model.attribute('order'),
  url: Model.attribute('url')
});
