<?php

use Discuz\Database\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterBigintToAll extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->schema()->table('user_wechats', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
        });
        $this->schema()->table('user_action_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
        });
        $this->schema()->table('thread_video', function (Blueprint $table) {
            $table->unsignedBigInteger('thread_id')->change();
            $table->unsignedBigInteger('post_id')->change();
            $table->unsignedBigInteger('user_id')->change();
        });
        $this->schema()->table('user_action_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
        });
        $this->schema()->table('stop_words', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
        });
        $this->schema()->table('posts', function (Blueprint $table) {
            $table->unsignedBigInteger('thread_id')->change();
            $table->unsignedBigInteger('reply_post_id')->change();
            $table->unsignedBigInteger('reply_user_id')->change();
        });
        $this->schema()->table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id')->change();
            $table->unsignedBigInteger('thread_id')->change();
        });
        $this->schema()->table('invites', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id')->change();
            $table->unsignedBigInteger('user_id')->change();
            $table->unsignedBigInteger('to_user_id')->change();
        });
        $this->schema()->table('group_permission', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id')->change();
        });
        $this->schema()->table('group_paid_users', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id')->change();
        });
        $this->schema()->table('dialog_message', function (Blueprint $table) {
            $table->unsignedBigInteger('attachment_id')->change();
        });
        $this->schema()->table('attachments', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
            $table->unsignedBigInteger('type_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->schema()->table('user_wechats', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->change();
        });
        $this->schema()->table('user_action_logs', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->change();
        });
        $this->schema()->table('thread_video', function (Blueprint $table) {
            $table->unsignedInteger('thread_id')->change();
            $table->unsignedInteger('post_id')->change();
            $table->unsignedInteger('user_id')->change();
        });
        $this->schema()->table('user_action_logs', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->change();
        });
        $this->schema()->table('stop_words', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->change();
        });
        $this->schema()->table('posts', function (Blueprint $table) {
            $table->unsignedInteger('thread_id')->change();
            $table->unsignedInteger('reply_post_id')->change();
            $table->unsignedInteger('reply_user_id')->change();
        });
        $this->schema()->table('orders', function (Blueprint $table) {
            $table->unsignedInteger('group_id')->change();
            $table->unsignedInteger('thread_id')->change();
        });
        $this->schema()->table('invites', function (Blueprint $table) {
            $table->unsignedInteger('group_id')->change();
            $table->unsignedInteger('user_id')->change();
            $table->unsignedInteger('to_user_id')->change();
        });
        $this->schema()->table('group_permission', function (Blueprint $table) {
            $table->unsignedInteger('group_id')->change();
        });
        $this->schema()->table('group_paid_users', function (Blueprint $table) {
            $table->unsignedInteger('group_id')->change();
        });
        $this->schema()->table('dialog_message', function (Blueprint $table) {
            $table->unsignedInteger('attachment_id')->change();
        });
        $this->schema()->table('attachments', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->change();
            $table->unsignedInteger('type_id')->change();
        });
    }
}
