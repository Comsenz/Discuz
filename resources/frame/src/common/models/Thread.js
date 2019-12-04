import Model from '../Model';
import computed from '../utils/computed';

export default class Threads extends Model {}

Object.assign(Threads.prototype, {
  createdAt: Model.attribute('createdAt'),
  isApproved: Model.attribute('isApproved'),
  isEssence: Model.attribute('isEssence'),
  isSticky: Model.attribute('isSticky'),
  likeCount: Model.attribute('likeCount'),
  postCount: Model.attribute('postCount'),
  price: Model.attribute('price'),
  title: Model.attribute('title'),
  updatedAt: Model.attribute('updatedAt'),
  viewCount: Model.attribute('viewCount'),
  user:Model.hasOne('user'),
  firstPost: Model.hasOne('firstPost'),
  posts: Model.hasMany('posts'),
  lastThreePosts: Model.hasMany('lastThreePosts'),
  rewardedUsers:Model.hasMany('rewardedUsers'),
  canDelete: Model.attribute('canDelete'),
  isDeleted: computed('isDeleted', isDeleted => !!isDeleted),

});
