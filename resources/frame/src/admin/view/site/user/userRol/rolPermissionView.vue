<template>
  <div class="rol-permission-box">
    <!-- 设置权限子菜单 -->
    <div class="index-main-con__main-title__class permission__title">
      <i></i>
      <span
        v-for="(item, index) in menuData"
        :key="index"
        :class="activeTab.name === item.name ? 'is-active' : ''"
        @click="activeTab = item"
        >{{ item.title }}</span
      >
    </div>
    <Card
      :header="$router.history.current.query.name + '--' + activeTab.title"
    ></Card>
    <div class="scope-action" v-if="activeTab.title == '操作权限'">
      生效范围
    </div>
    <!-- 操作权限 -->
    <div v-show="activeTab.name === 'userOperate'">
      <div class="user-operate">
        <Card header="内容发布权限"></Card>
        <Card>
          <CardRow description="允许发布文字帖">
            <el-checkbox
              v-model="checked"
              label="createThread.0"
              :disabled="
                $router.history.current.query.id === '1' ||
                  $router.history.current.query.id === '7'
              "
              >发布文字</el-checkbox
            >
          </CardRow>
        </Card>
        <Card>
          <CardRow description="允许发布长文帖">
            <el-checkbox
              v-model="checked"
              label="createThread.1"
              :disabled="
                $router.history.current.query.id === '1' ||
                  $router.history.current.query.id === '7'
              "
              >发布帖子</el-checkbox
            >
          </CardRow>
        </Card>
        <Card>
          <CardRow description="允许发布视频帖">
            <el-checkbox
              v-model="checked"
              label="createThread.2"
              :disabled="
                videoDisabled ||
                  $router.history.current.query.id === '1' ||
                  $router.history.current.query.id === '7'
              "
              >发布视频</el-checkbox
            >
          </CardRow>
        </Card>
        <Card>
          <CardRow description="允许发布图片帖">
            <el-checkbox
              v-model="checked"
              label="createThread.3"
              :disabled="
                $router.history.current.query.id === '1' ||
                  $router.history.current.query.id === '7'
              "
              >发布图片</el-checkbox
            >
          </CardRow>
        </Card>
        <Card>
          <CardRow description="允许发布语音帖">
            <el-checkbox
              v-model="checked"
              label="createThread.4"
              :disabled="
                $router.history.current.query.id === '1' ||
                  $router.history.current.query.id === '7'
              "
              >发布语音</el-checkbox
            >
          </CardRow>
        </Card>
        <Card>
          <CardRow
            description="允许发布问答，只有在开启微信支付且允许发布付费内容时才能设置提问价格"
          >
            <el-checkbox
              v-model="checked"
              label="createThread.5"
              :disabled="
                $router.history.current.query.id === '1' ||
                  $router.history.current.query.id === '7'
              "
              >发布问答</el-checkbox
            >
          </CardRow>
        </Card>
        <Card>
          <CardRow description="允许发布商品帖">
            <el-checkbox
              v-model="checked"
              label="createThread.6"
              :disabled="
                $router.history.current.query.id === '1' ||
                  $router.history.current.query.id === '7'
              "
              >发布商品</el-checkbox
            >
          </CardRow>
        </Card>
        <Card>
          <CardRow description="允许发布私信">
            <el-checkbox
              v-model="checked"
              label="dialog.create"
              :disabled="
                $router.history.current.query.id === '1' ||
                  $router.history.current.query.id === '7'
              "
              >发布私信</el-checkbox
            >
          </CardRow>
        </Card>
        <Card>
          <CardRow description="允许成为发布问答时的提问对象">
            <el-checkbox
              v-model="checked"
              label="canBeAsked"
              :disabled="
                $router.history.current.query.id === '1' ||
                  $router.history.current.query.id === '7'
              "
              >允许被提问</el-checkbox
            >
          </CardRow>
        </Card>
        <Card>
          <CardRow description="允许在发布问答时设置围观">
            <el-checkbox
              v-model="checked"
              label="canBeOnlooker"
              :disabled="
                !canBeOnlooker ||
                  $router.history.current.query.id === '1' ||
                  $router.history.current.query.id === '7'
              "
              >设置围观</el-checkbox
            >
          </CardRow>
        </Card>
        <Card>
          <CardRow description="发布主题时上传附件的权限">
            <el-checkbox
              v-model="checked"
              label="attachment.create.0"
              :disabled="
                $router.history.current.query.id === '1' ||
                  $router.history.current.query.id === '7'
              "
              >上传附件</el-checkbox
            >
          </CardRow>
        </Card>
        <Card>
          <CardRow description="发布主题时上传图片的权限">
            <el-checkbox
              v-model="checked"
              label="attachment.create.1"
              :disabled="
                $router.history.current.query.id === '1' ||
                  $router.history.current.query.id === '7'
              "
              >上传图片</el-checkbox
            >
          </CardRow>
        </Card>
        <Card>
          <CardRow description="允许发布付费内容、付费附件">
            <el-checkbox
              v-model="checked"
              label="createThreadPaid"
              :disabled="
                $router.history.current.query.id === '1' ||
                  $router.history.current.query.id === '7' ||
                  wechatPayment
              "
              >发布付费内容</el-checkbox
            >
          </CardRow>
        </Card>
        <Card class="hasSelect">
          <CardRow description="允许发布帖子">
            <el-checkbox
              :disabled="
                $router.history.current.query.id === '1' ||
                  $router.history.current.query.id === '7'
              "
              >发布主题</el-checkbox
            >
          </CardRow>
          <el-select
            v-model="selectList.createThread"
            @remove-tag="clearItem($event, 'createThread')"
            multiple
            collapse-tags
            placeholder="请选择"
          >
            <el-option
              @click.native="changeCategory('createThread', item.id)"
              v-for="item in categoriesList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            >
            </el-option>
          </el-select>
        </Card>
        <Card class="hasSelect">
          <CardRow description="回复主题的权限">
            <el-checkbox
              :disabled="
                $router.history.current.query.id === '1' ||
                  $router.history.current.query.id === '7'
              "
              >回复主题</el-checkbox
            >
          </CardRow>
          <el-select
            v-model="selectList['thread.reply']"
            @remove-tag="clearItem($event, 'thread.reply')"
            multiple
            collapse-tags
            placeholder="请选择"
          >
            <el-option
              @click.native="changeCategory('thread.reply', item.id)"
              v-for="item in categoriesList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            >
            </el-option>
          </el-select>
        </Card>
        <Card class="hasSelect">
          <CardRow description="内容允许被打赏">
            <el-checkbox
              :disabled="
                $router.history.current.query.id === '1' ||
                  $router.history.current.query.id === '7' ||
                  wechatPayment
              "
              >允许被打赏</el-checkbox
            >
          </CardRow>
          <el-select
            v-model="selectList['thread.canBeReward']"
            multiple
            collapse-tags
            @remove-tag="clearItem($event, 'thread.canBeReward')"
            placeholder="请选择"
          >
            <el-option
              @click.native="changeCategory('thread.canBeReward', item.id)"
              v-for="item in categoriesList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            >
            </el-option>
          </el-select>
        </Card>
      </div>
      <div class="user-operate">
        <Card header="查看权限"></Card>
        <Card class="hasSelect">
          <CardRow description="查看主题列表页的权限">
            <el-checkbox :disabled="$router.history.current.query.id === '1'"
              >查看主题列表</el-checkbox
            >
          </CardRow>
          <el-select
            v-model="selectList.viewThreads"
            @remove-tag="clearItem($event, 'viewThreads')"
            multiple
            collapse-tags
            placeholder="请选择"
          >
            <el-option
              @click.native="changeCategory('viewThreads', item.id)"
              v-for="item in categoriesList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            >
            </el-option>
          </el-select>
        </Card>
        <Card class="hasSelect">
          <CardRow description="查看主题的详情页的权限">
            <el-checkbox :disabled="$router.history.current.query.id === '1'"
              >查看主题详情</el-checkbox
            >
          </CardRow>
          <el-select
            v-model="selectList['thread.viewPosts']"
            @remove-tag="clearItem($event, 'viewPosts')"
            multiple
            collapse-tags
            placeholder="请选择"
          >
            <el-option
              @click.native="changeCategory('thread.viewPosts', item.id)"
              v-for="item in categoriesList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            >
            </el-option>
          </el-select>
        </Card>
        <Card class="hasSelect">
          <CardRow description="免费查看付费帖子">
            <el-checkbox :disabled="$router.history.current.query.id === '1'"
              >免费查看付费帖子</el-checkbox
            >
          </CardRow>
          <el-select
            v-model="selectList['thread.freeViewPosts.1']"
            @remove-tag="clearItem($event, 'thread.freeViewPosts.1')"
            multiple
            collapse-tags
            placeholder="请选择"
          >
            <el-option
              @click.native="changeCategory('thread.freeViewPosts.1', item.id)"
              v-for="item in categoriesList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            >
            </el-option>
          </el-select>
        </Card>
        <Card class="hasSelect">
          <CardRow description="免费查看付费视频">
            <el-checkbox :disabled="$router.history.current.query.id === '1'"
              >免费查看付费视频</el-checkbox
            >
          </CardRow>
          <el-select
            v-model="selectList['thread.freeViewPosts.2']"
            @remove-tag="clearItem($event, 'thread.freeViewPosts.2')"
            multiple
            collapse-tags
            placeholder="请选择"
          >
            <el-option
              @click.native="changeCategory('thread.freeViewPosts.2', item.id)"
              v-for="item in categoriesList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            >
            </el-option>
          </el-select>
        </Card>
        <Card class="hasSelect">
          <CardRow description="免费查看付费图片">
            <el-checkbox :disabled="$router.history.current.query.id === '1'"
              >免费查看付费图片</el-checkbox
            >
          </CardRow>
          <el-select
            v-model="selectList['thread.freeViewPosts.3']"
            @remove-tag="clearItem($event, 'thread.freeViewPosts.3')"
            multiple
            collapse-tags
            placeholder="请选择"
          >
            <el-option
              @click.native="changeCategory('thread.freeViewPosts.3', item.id)"
              v-for="item in categoriesList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            >
            </el-option>
          </el-select>
        </Card>
        <Card class="hasSelect">
          <CardRow description="免费查看付费语音">
            <el-checkbox :disabled="$router.history.current.query.id === '1'"
              >免费查看付费语音</el-checkbox
            >
          </CardRow>
          <el-select
            v-model="selectList['thread.freeViewPosts.4']"
            @remove-tag="clearItem($event, 'thread.freeViewPosts.4')"
            multiple
            collapse-tags
            placeholder="请选择"
          >
            <el-option
              @click.native="changeCategory('thread.freeViewPosts.4', item.id)"
              v-for="item in categoriesList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            >
            </el-option>
          </el-select>
        </Card>
        <Card class="hasSelect">
          <CardRow description="免费查看付费问答">
            <el-checkbox :disabled="$router.history.current.query.id === '1'"
              >免费查看付费问答</el-checkbox
            >
          </CardRow>
          <el-select
            v-model="selectList['thread.freeViewPosts.5']"
            @remove-tag="clearItem($event, 'thread.freeViewPosts.5')"
            multiple
            collapse-tags
            placeholder="请选择"
          >
            <el-option
              @click.native="changeCategory('thread.freeViewPosts.5', item.id)"
              v-for="item in categoriesList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            >
            </el-option>
          </el-select>
        </Card>
      </div>
      <div class="user-operate">
        <Card header="管理权限"></Card>
        <Card>
          <CardRow description="前台置顶、取消置顶主题的权限">
            <el-checkbox
              v-model="checked"
              label="thread.sticky"
              :disabled="
                $router.history.current.query.id === '1' ||
                  $router.history.current.query.id === '7'
              "
              >置顶</el-checkbox
            >
          </CardRow>
        </Card>
        <Card>
          <CardRow description="前台批量管理主题的权限">
            <el-checkbox
              v-model="checked"
              label="thread.batchEdit"
              :disabled="
                $router.history.current.query.id === '1' ||
                  $router.history.current.query.id === '7'
              "
              >批量管理主题</el-checkbox
            >
          </CardRow>
        </Card>
        <Card>
          <CardRow description="前台按用户组邀请成员的权限">
            <el-checkbox
              v-model="checked"
              label="createInvite"
              :disabled="
                $router.history.current.query.id === '1' ||
                  $router.history.current.query.id === '7'
              "
              >管理-邀请加入</el-checkbox
            >
          </CardRow>
        </Card>
        <Card>
          <CardRow description="前台更改成员所属用户组的权限">
            <el-checkbox
              v-model="checked"
              label="user.edit.group"
              :disabled="
                $router.history.current.query.id === '1' ||
                  $router.history.current.query.id === '7'
              "
              >编辑用户组</el-checkbox
            >
          </CardRow>
        </Card>
        <Card>
          <CardRow description="前台更改成员禁用状态的权限">
            <el-checkbox
              v-model="checked"
              label="user.edit.status"
              :disabled="
                $router.history.current.query.id === '1' ||
                  $router.history.current.query.id === '7'
              "
              >编辑用户状态</el-checkbox
            >
          </CardRow>
        </Card>
        <Card class="hasSelect">
          <CardRow description="前台删除单个主题的权限">
            <el-checkbox
              :disabled="
                $router.history.current.query.id === '1' ||
                  $router.history.current.query.id === '7'
              "
              >删主题</el-checkbox
            >
          </CardRow>
          <el-select
            v-model="selectList['thread.hide']"
            @remove-tag="clearItem($event, 'thread.hide')"
            multiple
            collapse-tags
            placeholder="请选择"
          >
            <el-option
              @click.native="changeCategory('thread.hide', item.id)"
              v-for="item in categoriesList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            >
            </el-option>
          </el-select>
        </Card>
        <Card class="hasSelect">
          <CardRow description="前台精华、取消精华主题的权限">
            <el-checkbox
              :disabled="
                $router.history.current.query.id === '1' ||
                  $router.history.current.query.id === '7'
              "
              >加精</el-checkbox
            >
          </CardRow>
          <el-select
            v-model="selectList['thread.essence']"
            @remove-tag="clearItem($event, 'thread.essence')"
            multiple
            collapse-tags
            placeholder="请选择"
          >
            <el-option
              @click.native="changeCategory('thread.essence', item.id)"
              v-for="item in categoriesList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            >
            </el-option>
          </el-select>
        </Card>
        <Card class="hasSelect">
          <CardRow description="前台删除单个回复的权限">
            <el-checkbox
              :disabled="
                $router.history.current.query.id === '1' ||
                  $router.history.current.query.id === '7'
              "
              >删回复</el-checkbox
            >
          </CardRow>
          <el-select
            v-model="selectList['thread.hidePosts']"
            @remove-tag="clearItem($event, 'thread.hidePosts')"
            multiple
            collapse-tags
            placeholder="请选择"
          >
            <el-option
              @click.native="changeCategory('thread.hidePosts', item.id)"
              v-for="item in categoriesList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            >
            </el-option>
          </el-select>
        </Card>
        <Card class="hasSelect">
          <CardRow description="前台单个主题的编辑权限">
            <el-checkbox
              :disabled="
                $router.history.current.query.id === '1' ||
                  $router.history.current.query.id === '7'
              "
              >编辑主题</el-checkbox
            >
          </CardRow>
          <el-select
            v-model="selectList['thread.edit']"
            @remove-tag="clearItem($event, 'thread.edit')"
            multiple
            collapse-tags
            placeholder="请选择"
          >
            <el-option
              @click.native="changeCategory('thread.edit', item.id)"
              v-for="item in categoriesList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            >
            </el-option>
          </el-select>
        </Card>
        <Card class="hasSelect">
          <CardRow description="前台单个回复的编辑权限">
            <el-checkbox
              :disabled="
                $router.history.current.query.id === '1' ||
                  $router.history.current.query.id === '7'
              "
              >编辑回复</el-checkbox
            >
          </CardRow>
          <el-select
            v-model="selectList['thread.editPosts']"
            @remove-tag="clearItem($event, 'thread.editPosts')"
            multiple
            collapse-tags
            placeholder="请选择"
          >
            <el-option
              @click.native="changeCategory('thread.editPosts', item.id)"
              v-for="item in categoriesList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            >
            </el-option>
          </el-select>
        </Card>
        <Card class="hasSelect">
          <CardRow description="作者编辑自己的主题或回复的权限">
            <el-checkbox
              :disabled="
                $router.history.current.query.id === '1' ||
                  $router.history.current.query.id === '7'
              "
              >编辑自己的主题或回复</el-checkbox
            >
          </CardRow>
          <el-select
            v-model="selectList.editOwnThreadOrPost"
            @remove-tag="clearItem($event, 'editOwnThreadOrPost')"
            multiple
            collapse-tags
            placeholder="请选择"
          >
            <el-option
              @click.native="changeCategory('editOwnThreadOrPost', item.id)"
              v-for="item in categoriesList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            >
            </el-option>
          </el-select>
        </Card>
        <Card class="hasSelect">
          <CardRow description="作者删除自己的主题或回复的权限">
            <el-checkbox
              :disabled="
                $router.history.current.query.id === '1' ||
                  $router.history.current.query.id === '7'
              "
              >删除自己的主题或回复</el-checkbox
            >
          </CardRow>
          <el-select
            v-model="selectList.hideOwnThreadOrPost"
            @remove-tag="clearItem($event, 'hideOwnThreadOrPost')"
            multiple
            collapse-tags
            placeholder="请选择"
          >
            <el-option
              @click.native="changeCategory('hideOwnThreadOrPost', item.id)"
              v-for="item in categoriesList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            >
            </el-option>
          </el-select>
        </Card>
      </div>
    </div>
    <!-- 安全设置 -->
    <div v-show="activeTab.name === 'security'">
      <Card>
        <CardRow description="启用验证码需先在腾讯云设置中开启验证码服务">
          <el-checkbox
            v-model="checked"
            label="createThreadWithCaptcha"
            :disabled="
              captchaDisabled ||
                $router.history.current.query.id === '1' ||
                $router.history.current.query.id === '7'
            "
            >发布内容时启用验证码</el-checkbox
          >
        </CardRow>
      </Card>

      <Card>
        <CardRow description="实名认证后才可发布内容">
          <el-checkbox
            v-model="checked"
            label="publishNeedRealName"
            :disabled="
              realNameDisabled ||
                $router.history.current.query.id === '1' ||
                $router.history.current.query.id === '7'
            "
            >发布内容需先实名认证</el-checkbox
          >
        </CardRow>
      </Card>

      <Card>
        <CardRow description="绑定手机后才可发布内容">
          <el-checkbox
            v-model="checked"
            label="publishNeedBindPhone"
            :disabled="
              bindPhoneDisabled ||
                $router.history.current.query.id === '1' ||
                $router.history.current.query.id === '7'
            "
            >发布内容需先绑定手机</el-checkbox
          >
        </CardRow>
      </Card>
    </div>
    <!-- 默认权限 -->
    <div v-show="activeTab.name === 'default'">
      <Card>
        <CardRow description>
          <p style="margin-left: 24PX">站点信息</p>
        </CardRow>
      </Card>

      <Card>
        <CardRow description>
          <p style="margin-left: 24PX">主题点赞</p>
        </CardRow>
      </Card>

      <Card>
        <CardRow description>
          <p style="margin-left: 24PX">主题收藏</p>
        </CardRow>
      </Card>

      <Card>
        <CardRow description>
          <p style="margin-left: 24PX">主题打赏</p>
        </CardRow>
      </Card>
    </div>
    <!-- 其他权限 -->
    <div v-show="activeTab.name === 'other'">
      <Card header="裂变推广：">
        <CardRow
          description="允许用户裂变推广以及通过推广注册进来的用户收入是否能分成"
        >
          <el-checkbox
            v-model="is_subordinate"
            @change="handlePromotionChange"
            :disabled="
              $router.history.current.query.id === '1' ||
                $router.history.current.query.id === '7'
            "
            >裂变推广</el-checkbox
          >
          <el-checkbox
            v-model="is_commission"
            @change="handleScaleChange"
            :disabled="
              $router.history.current.query.id === '1' ||
                $router.history.current.query.id === '7'
            "
            >收入分成</el-checkbox
          >
        </CardRow>
        <CardRow
          description="站点开启付费模式时下线付费加入、主题被打赏、被付费等的分成比例设置，填1表示10%，不填或为0时为不分成"
          class="proportion-box"
          v-if="is_subordinate || is_commission"
        >
          <div>
            <span>提成比例</span>
            <el-input
              class
              type="number"
              v-model="scale"
              @blur="checkNum"
            ></el-input>
          </div>
        </CardRow>
      </Card>
    </div>
    <!-- 价格设置 -->
    <div v-show="activeTab.name === 'pricesetting'">
      <Card header="允许购买：">
        <CardRow description="允许购买" class="allow-box">
          <el-switch
            :disabled="
              $router.history.current.query.id === '1' ||
                $router.history.current.query.id === '7' ||
                !allowtobuy ||
                defaultuser
            "
            v-model="value"
            @change="fun"
            active-color="#336699"
            inactive-color="#bbbbbb"
          >
          </el-switch>
        </CardRow>
      </Card>
      <Card header="购买价格（元）：" v-if="value">
        <CardRow description="需支付的金额">
          <el-input
            placeholder="加入价格"
            type="number"
            v-model="purchasePrice"
            @input="addprice"
          ></el-input>
        </CardRow>
      </Card>
      <Card header="到期时间：" v-if="value">
        <CardRow description="到期时间，可维持的时间">
          加入起
          <el-input
            class="elinput"
            style="height: 36PX;width: 80PX"
            clearable
            placeholder="天数"
            type="number"
            @input="duedata"
            v-model="dyedate"
          ></el-input>
          天后
        </CardRow>
      </Card>
    </div>
    <Card class="footer-btn">
      <el-button size="medium" type="primary" @click="submitClick"
        >提交</el-button
      >
    </Card>
  </div>
</template>

<script>
import "../../../../scss/site/module/userStyle.scss";
import rolPermissionCon from "../../../../controllers/site/user/userRol/rolPermissionCon";
// import '../../../scss/site/module/contStyle.scss';
export default {
  name: "user-permission-view",
  ...rolPermissionCon
  // ...contClassConfigure,
};
</script>
