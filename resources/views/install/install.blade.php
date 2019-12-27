@extends('install.app')


@section('content')
<h2>安装 Discuz! Q</h2>

<p>请查看安装文档，根据文档完成安装 <a href="https://www.discuz.net/docs/install.html" target="_blank">Discuz! Q</a>.</p>

<form method="post">
  <div id="error" style="display:none"></div>

  <div class="FormGroup">
    <div class="FormField">
      <label>站点名称</label>
      <input name="forumTitle">
    </div>
  </div>

  <div class="FormGroup">
    <div class="FormField">
      <label>MySQL Host</label>
      <input name="mysqlHost" value="localhost">
    </div>

    <div class="FormField">
      <label>MySQL 数据库</label>
      <input name="mysqlDatabase">
    </div>

    <div class="FormField">
      <label>MySQL 用户名</label>
      <input name="mysqlUsername">
    </div>

    <div class="FormField">
      <label>MySQL 密码</label>
      <input type="password" name="mysqlPassword">
    </div>

    <div class="FormField">
      <label>表前缀</label>
      <input type="text" name="tablePrefix">
    </div>
  </div>

  <div class="FormGroup">
    <div class="FormField">
      <label>管理员 用户名</label>
      <input name="adminUsername">
    </div>

    <div class="FormField">
      <label>管理员 密码</label>
      <input type="password" name="adminPassword">
    </div>

    <div class="FormField">
      <label>管理员 确认密码</label>
      <input type="password" name="adminPasswordConfirmation">
    </div>
  </div>

  <div class="FormButtons">
    <button type="submit">安装</button>
  </div>
</form>

<script src="assets/js/jquery.min.js"></script>
<script>
$(function() {
  $('form :input:first').select();

  $('form').on('submit', function(e) {
    e.preventDefault();

    let $button = $(this).find('button')
      .text('请稍等...')
      .prop('disabled', true);

    $.post('', $(this).serialize())
      .done(function(data) {
          localStorage.setItem('officeDb_Authorization', data.token);
          window.location.href = '/';
      })
      .fail(function(data) {
        $('#error').show().text('安装出错:\n\n' + data.responseText);

        $button.prop('disabled', false).text('安装');
      });

    return false;
  });
});
</script>
@endsection
