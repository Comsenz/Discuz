import Model from '../Model';

export default class Groups extends Model {
}

Object.assign(Groups.prototype, {
  name: Model.attribute('name')
});
