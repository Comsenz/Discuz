import Model from '../Model';

export default class Post extends Model {}

Object.assign(Post.prototype, {
  content: Model.attribute('content'),
  createdAt: Model.attribute('createdAt'),
  ip: Model.attribute('ip'),
  isApproved: Model.attribute('isApproved'),
  isFirst: Model.attribute('isFirst'),
  isLiked: Model.attribute('isLiked'),
  likeCount: Model.attribute('likeCount'),
  replyCount: Model.attribute('replyCount'),
  updatedAt: Model.attribute('updatedAt'),
  user:Model.hasOne('user'),
  thread:Model.hasOne('thread'),
  replyUser:Model.hasOne('user'),
  likedUsers:Model.hasMany('likedUsers')
});
