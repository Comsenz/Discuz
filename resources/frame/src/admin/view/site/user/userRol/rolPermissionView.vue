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
    <Card :header="$router.history.current.query.name + activeTab.title"></Card>
    <!-- 内容发布权限 -->
    <div v-show="activeTab.name === 'publish'">
      <Card>
        <CardRow description="允许发布文字帖">
          <el-checkbox
            v-model="checked"
            label="createThread"
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
            label="createThreadLong"
            :disabled="
              $router.history.current.query.id === '1' ||
                $router.history.current.query.id === '7'
            "
            >发布帖子</el-checkbox
          >
        </CardRow>
      </Card>

      <Card>
        <CardRow description="允许发布图片帖">
          <el-checkbox
            v-model="checked"
            label="createThreadImage"
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
            label="createThreadAudio"
            :disabled="
              $router.history.current.query.id === '1' ||
                $router.history.current.query.id === '7'
            "
            >发布语音</el-checkbox
          >
        </CardRow>
      </Card>

      <Card>
        <CardRow description="允许发布视频帖">
          <el-checkbox
            v-model="checked"
            label="createThreadVideo"
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
        <CardRow description="允许发布商品帖">
          <el-checkbox
            v-model="checked"
            label="createThreadGoods"
            :disabled="
              $router.history.current.query.id === '1' ||
                $router.history.current.query.id === '7'
            "
            >发布商品</el-checkbox
          >
        </CardRow>
      </Card>
      <Card>
        <CardRow
          description="允许发布问答，只有在开启微信支付且允许发布付费内容时才能设置提问价格"
        >
          <el-checkbox
            v-model="checked"
            label="createThreadQuestion"
            :disabled="
              $router.history.current.query.id === '1' ||
                $router.history.current.query.id === '7'
            "
            >发布问答</el-checkbox
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
        <CardRow description="回复主题的权限">
          <el-checkbox
            v-model="checked"
            label="thread.reply"
            :disabled="
              $router.history.current.query.id === '1' ||
                $router.history.current.query.id === '7'
            "
            >回复主题</el-checkbox
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

      <Card>
        <CardRow description="内容允许被打赏">
          <el-checkbox
            v-model="checked"
            label="canBeReward"
            :disabled="
              $router.history.current.query.id === '1' ||
                $router.history.current.query.id === '7' ||
                wechatPayment
            "
            >允许被打赏</el-checkbox
          >
        </CardRow>
      </Card>
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
    <!-- 前台操作权限 -->
    <div v-show="activeTab.name === 'operate'">
      <Card>
        <CardRow description="查看主题列表页的权限">
          <el-checkbox
            v-model="checked"
            label="viewThreads"
            :disabled="$router.history.current.query.id === '1'"
            >查看主题列表</el-checkbox
          >
        </CardRow>
      </Card>

      <Card>
        <CardRow description="查看主题的详情页的权限">
          <el-checkbox
            v-model="checked"
            label="thread.viewPosts"
            :disabled="$router.history.current.query.id === '1'"
            >查看主题详情</el-checkbox
          >
        </CardRow>
      </Card>

      <!-- <Card>
        <CardRow description="查看主题的详情页中的附件的权限">
          <el-checkbox v-model="checked" label="attachment.view.0">查看附件</el-checkbox>
        </CardRow>
      </Card>

      <Card>
        <CardRow description="查看主题的详情页中的图片的权限">
          <el-checkbox v-model="checked" label="attachment.view.1">查看图片</el-checkbox>
        </CardRow>
      </Card>-->
      <!-- $router.history.current.query.id === '7' -->
      <Card>
        <CardRow description="查看站点成员列表、搜索成员的权限">
          <el-checkbox
            v-model="checked"
            label="viewUserList"
            :disabled="$router.history.current.query.id === '1'"
            >站点会员列表</el-checkbox
          >
        </CardRow>
      </Card>

      <Card>
        <CardRow description="作者编辑自己的主题或回复的权限">
          <el-checkbox
            v-model="checked"
            label="editOwnThreadOrPost"
            :disabled="
              $router.history.current.query.id === '1' ||
                $router.history.current.query.id === '7'
            "
            >编辑自己的主题或回复</el-checkbox
          >
        </CardRow>
      </Card>

      <Card>
        <CardRow description="作者删除自己的主题或回复的权限">
          <el-checkbox
            v-model="checked"
            label="hideOwnThreadOrPost"
            :disabled="
              $router.history.current.query.id === '1' ||
                $router.history.current.query.id === '7'
            "
            >删除自己的主题或回复</el-checkbox
          >
        </CardRow>
      </Card>
    </div>
    <!-- 前台管理权限 -->
    <div v-show="activeTab.name === 'manage'">
      <Card>
        <CardRow description="前台删除单个主题的权限">
          <el-checkbox
            v-model="checked"
            label="thread.hide"
            :disabled="
              $router.history.current.query.id === '1' ||
                $router.history.current.query.id === '7'
            "
            >删主题</el-checkbox
          >
        </CardRow>
      </Card>

      <Card>
        <CardRow description="前台删除单个回复的权限">
          <el-checkbox
            v-model="checked"
            label="thread.hidePosts"
            :disabled="
              $router.history.current.query.id === '1' ||
                $router.history.current.query.id === '7'
            "
            >删回复</el-checkbox
          >
        </CardRow>
      </Card>

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
        <CardRow description="前台精华、取消精华主题的权限">
          <el-checkbox
            v-model="checked"
            label="thread.essence"
            :disabled="
              $router.history.current.query.id === '1' ||
                $router.history.current.query.id === '7'
            "
            >加精</el-checkbox
          >
        </CardRow>
      </Card>

      <Card>
        <CardRow description="前台单个主题的编辑权限">
          <el-checkbox
            v-model="checked"
            label="thread.editPosts"
            :disabled="
              $router.history.current.query.id === '1' ||
                $router.history.current.query.id === '7'
            "
            >编辑</el-checkbox
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
    <!-- 分类权限 -->
    <div v-show="activeTab.name === 'category'" class="cont-class-box">
      <div class="cont-class-table">
        <el-table
          ref="multipleTable"
          :data="categoriesList"
          style="width: 100%"
          tooltip-effect="dark"
        >
          <el-table-column width="50">
            <el-checkbox
              :id="scope.row.id"
              slot-scope="scope"
              v-model="scope.row.checkAll"
              :indeterminate="scope.row.isIndeterminate"
              @change="handleCheckAllChange(scope.row)"
            >
            </el-checkbox>
          </el-table-column>

          <el-table-column label="分类">
            <template slot-scope="scope">{{ scope.row.name }}</template>
          </el-table-column>

          <el-table-column label="浏览分类">
            <el-checkbox
              slot-scope="scope"
              v-model="checked"
              :label="`category${scope.row.id}.viewThreads`"
              @change="handleCheckedCategoryPermissionsChange(scope.row)"
              >{{ "" }}
            </el-checkbox>
          </el-table-column>

          <el-table-column label="发表内容">
            <el-checkbox
              slot-scope="scope"
              v-model="checked"
              :disabled="
                checked.indexOf(`category${scope.row.id}.viewThreads`) === -1 ||
                  groupId === '7'
              "
              :label="`category${scope.row.id}.createThread`"
              @change="handleCheckedCategoryPermissionsChange(scope.row)"
              >{{ "" }}
            </el-checkbox>
          </el-table-column>

          <el-table-column label="发表评论">
            <el-checkbox
              slot-scope="scope"
              v-model="checked"
              :disabled="
                checked.indexOf(`category${scope.row.id}.viewThreads`) === -1 ||
                  groupId === '7'
              "
              :label="`category${scope.row.id}.replyThread`"
              @change="handleCheckedCategoryPermissionsChange(scope.row)"
              >{{ "" }}
            </el-checkbox>
          </el-table-column>

          <el-table-column label="编辑内容">
            <el-checkbox
              slot-scope="scope"
              v-model="checked"
              :disabled="
                checked.indexOf(`category${scope.row.id}.viewThreads`) === -1 ||
                  groupId === '7'
              "
              :label="`category${scope.row.id}.thread.edit`"
              @change="handleCheckedCategoryPermissionsChange(scope.row)"
              >{{ "" }}
            </el-checkbox>
          </el-table-column>

          <el-table-column label="删除内容">
            <el-checkbox
              slot-scope="scope"
              v-model="checked"
              :disabled="
                checked.indexOf(`category${scope.row.id}.viewThreads`) === -1 ||
                  groupId === '7'
              "
              :label="`category${scope.row.id}.thread.hide`"
              @change="handleCheckedCategoryPermissionsChange(scope.row)"
              >{{ "" }}
            </el-checkbox>
          </el-table-column>

          <el-table-column label="加精内容">
            <el-checkbox
              slot-scope="scope"
              v-model="checked"
              :disabled="
                checked.indexOf(`category${scope.row.id}.viewThreads`) === -1 ||
                  groupId === '7'
              "
              :label="`category${scope.row.id}.thread.essence`"
              @change="handleCheckedCategoryPermissionsChange(scope.row)"
              >{{ "" }}
            </el-checkbox>
          </el-table-column>
        </el-table>
      </div>
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
