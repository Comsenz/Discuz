<?php


use Illuminate\Database\Seeder;
use App\Models\CreditScoreRule;
use Carbon\Carbon;

class CreditScoreRuleTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();
        $rule = new CreditScoreRule();
        $rule->truncate();
        $rule->insert([
            ['rule' => '发帖', 'action' => 'NEW_TOPIC', 'cycle_type' => 0, 'interval_time'=> 0, 'reward_num' => 0, 'score' => 0,  'created_at' => $now, 'updated_at' => $now],
            ['rule' => '回帖', 'action' => 'REPLY_TOPIC', 'cycle_type' => 0, 'interval_time'=> 0, 'reward_num' => 0, 'score' => 0,  'created_at' => $now, 'updated_at' => $now],
            ['rule' => '评论', 'action' => 'COMMENT', 'cycle_type' => 0, 'interval_time'=> 0, 'reward_num' => 0, 'score' => 0,  'created_at' => $now, 'updated_at' => $now],
            ['rule' => '加精华', 'action' => 'ADD_ESSENCE', 'cycle_type' => 0, 'interval_time'=> 0, 'reward_num' => 0, 'score' => 0,  'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
