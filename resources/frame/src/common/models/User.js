import Model from '../Model';

export default class User extends Model {
  apiEndpoint(){
    return 'users';
  }
}

Object.assign(User.prototype, {
  createdAt: Model.attribute('createdAt'),
  lastLoginIp: Model.attribute('lastLoginIp'),
  mobile: Model.attribute('mobile'),
  nickname: Model.attribute('nickname'),
  unionId: Model.attribute('unionId'),
  updatedAt: Model.attribute('updatedAt'),
  username: Model.attribute('username'),
  password: Model.attribute('password')
});
