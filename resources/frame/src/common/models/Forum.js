import Model from '../Model';

export default class Forum extends Model {}

Object.assign(Forum.prototype, {
  siteMode: Model.attribute('siteMode'),
  logo: Model.attribute('logo'),
  siteName: Model.attribute('siteName'),
  siteIntroduction: Model.attribute('siteIntroduction'),
  siteInstall: Model.attribute('siteInstall'),
  siteAuthor: Model.attribute('siteAuthor'),
  price: Model.attribute('price'),
  day: Model.attribute('day'),
});
