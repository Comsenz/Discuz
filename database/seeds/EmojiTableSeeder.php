<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\Emoji;

class EmojiTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        $emoji = new Emoji;
        $emoji->truncate();
        $emoji->insert([
            ['category' => 'qq', 'url' => 'emoji/qq/kelian.gif', 'code' => ':kelian:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/haqian.gif', 'code' => ':haqian:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/woshou.gif', 'code' => ':woshou:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/aixin.gif', 'code' => ':aixin:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/zuohengheng.gif', 'code' => ':zuohengheng:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/weixiao.gif', 'code' => ':weixiao:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/jingkong.gif', 'code' => ':jingkong:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/tiaopi.gif', 'code' => ':tiaopi:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/touxiao.gif', 'code' => ':touxiao:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/youling.gif', 'code' => ':youling:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/caidao.gif', 'code' => ':caidao:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/cahan.gif', 'code' => ':cahan:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/hecai.gif', 'code' => ':hecai:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/keai.gif', 'code' => ':keai:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/ciya.gif', 'code' => ':ciya:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/saorao.gif', 'code' => ':saorao:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/jingxi.gif', 'code' => ':jingxi:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/ku.gif', 'code' => ':ku:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/piezui.gif', 'code' => ':piezui:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/se.gif', 'code' => ':se:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/xia.gif', 'code' => ':xia:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/yinxian.gif', 'code' => ':yinxian:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/zhouma.gif', 'code' => ':zhouma:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/kulou.gif', 'code' => ':kulou:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/xu.gif', 'code' => ':xu:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/jingya.gif', 'code' => ':jingya:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/doge.gif', 'code' => ':doge:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/bizui.gif', 'code' => ':bizui:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/yangtuo.gif', 'code' => ':yangtuo:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/shouqiang.gif', 'code' => ':shouqiang:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/baoquan.gif', 'code' => ':baoquan:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/yun.gif', 'code' => ':yun:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/lanqiu.gif', 'code' => ':lanqiu:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/zhemo.gif', 'code' => ':zhemo:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/guzhang.gif', 'code' => ':guzhang:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/shengli.gif', 'code' => ':shengli:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/zaijian.gif', 'code' => ':zaijian:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/dabing.gif', 'code' => ':dabing:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/deyi.gif', 'code' => ':deyi:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/hanxiao.gif', 'code' => ':hanxiao:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/kun.gif', 'code' => ':kun:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/hexie.gif', 'code' => ':hexie:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/daku.gif', 'code' => ':daku:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/wozuimei.gif', 'code' => ':wozuimei:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/xiaoku.gif', 'code' => ':xiaoku:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/xigua.gif', 'code' => ':xigua:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/huaixiao.gif', 'code' => ':huaixiao:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/liulei.gif', 'code' => ':liulei:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/lenghan.gif', 'code' => ':lenghan:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/qiudale.gif', 'code' => ':qiudale:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/zhayanjian.gif', 'code' => ':zhayanjian:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/qiaoda.gif', 'code' => ':qiaoda:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/baojin.gif', 'code' => ':baojin:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/OK.gif', 'code' => ':OK:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/xiaojiujie.gif', 'code' => ':xiaojiujie:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/gouyin.gif', 'code' => ':gouyin:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/youhengheng.gif', 'code' => ':youhengheng:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/tuosai.gif', 'code' => ':tuosai:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/nanguo.gif', 'code' => ':nanguo:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/quantou.gif', 'code' => ':quantou:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/haixiu.gif', 'code' => ':haixiu:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/koubi.gif', 'code' => ':koubi:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/qiang.gif', 'code' => ':qiang:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/pijiu.gif', 'code' => ':pijiu:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/bishi.gif', 'code' => ':bishi:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/yiwen.gif', 'code' => ':yiwen:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/liuhan.gif', 'code' => ':liuhan:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/wunai.gif', 'code' => ':wunai:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/aini.gif', 'code' => ':aini:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/bangbangtang.gif', 'code' => ':bangbangtang:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/penxue.gif', 'code' => ':penxue:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/haobang.gif', 'code' => ':haobang:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/qinqin.gif', 'code' => ':qinqin:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/xiaoyanger.gif', 'code' => ':xiaoyanger:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/fendou.gif', 'code' => ':fendou:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/ganga.gif', 'code' => ':ganga:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/shuai.gif', 'code' => ':shuai:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/juhua.gif', 'code' => ':juhua:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/baiyan.gif', 'code' => ':baiyan:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/fanu.gif', 'code' => ':fanu:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/jie.gif', 'code' => ':jie:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/chi.gif', 'code' => ':chi:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/kuaikule.gif', 'code' => ':kuaikule:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/zhuakuang.gif', 'code' => ':zhuakuang:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/shui.gif', 'code' => ':shui:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/dan.gif', 'code' => ':dan:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/aoman.gif', 'code' => ':aoman:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/fadai.gif', 'code' => ':fadai:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/leiben.gif', 'code' => ':leiben:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/tu.gif', 'code' => ':tu:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/weiqu.gif', 'code' => ':weiqu:', 'created_at' => $now, 'updated_at' => $now],
            ['category' => 'qq', 'url' => 'emoji/qq/xieyanxiao.gif', 'code' => ':xieyanxiao:', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
