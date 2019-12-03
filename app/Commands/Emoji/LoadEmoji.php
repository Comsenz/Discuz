<?php
/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: LoadEmoji.php 28830 2019-12-03 15:37 yanchen $
 */
namespace App\Commands\Emoji;

use App\Models\Emoji;
use App\Models\User;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;

class LoadEmoji
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    const EMOJI_PATH_NAME = 'emoji';
    const LEFT_DELIMITER = ':';
    const RIGHT_DELIMITER = ':';

    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * @var
     */
    public $category;

    /**
     * @var
     */
    public $public_path;

    public $model;

    /**
     * LoadEmoji constructor.
     * @param User $actor
     * @param $category
     */
    public function __construct(User $actor, $category)
    {
        $this->actor = $actor;
        $this->category = $category;
        $this->public_path = public_path();
    }

    public function handle(Dispatcher $events)
    {
        $this->events = $events;

//        $this->assertRegistered($this->actor);
//        $this->assertCan($this->actor, 'emoji.loadEmoji');


        if ($this->category == 'all'){
            $emojies = $this->loadEmoji('emoji');

            if ($emojies){

                foreach ($emojies as $category => $emojies_data){
                    //删除
                    Emoji::where('category', '=', $category)->delete();

                    $data = [];

                    foreach ($emojies_data as $emojy){
                        $code_name = self::LEFT_DELIMITER . substr(basename($emojy),0,strrpos(basename($emojy),'.')) . self::RIGHT_DELIMITER;

                        $data[] = ['url' => $emojy, 'code' => $code_name, 'category' => $category, 'created_at'=>date('Y-m-d H:i:s', time()), 'updated_at'=>date('Y-m-d H:i:s', time())];
                    }
                    Emoji::insert($data);
                }
            }
        }else{
            if (is_dir('emoji'.DIRECTORY_SEPARATOR.$this->category)){

                //删除
                Emoji::where('category', '=', $this->category)->delete();

                $emojies = $this->loadEmoji('emoji'.DIRECTORY_SEPARATOR.$this->category);

                foreach ($emojies as $emojy){

                    $code_name = self::LEFT_DELIMITER . substr(basename($emojy),0,strrpos(basename($emojy),'.')) . self::RIGHT_DELIMITER;

                    $data[] = ['url' => $emojy, 'code' => $code_name, 'category' => $this->category, 'created_at'=>date('Y-m-d H:i:s', time()), 'updated_at'=>date('Y-m-d H:i:s', time())];
                }
                Emoji::insert($data);
            }
        }
    }


    /**
     * load emoji files return array
     * @param $path
     * @return array
     */
    private function loadEmoji($path){
        $files = [];

        if(is_dir($path)) {
            $basename = basename($path);
            $dirs = opendir($path);

            if($dirs) {
                while(($file = readdir($dirs)) !== false) {

                    if($file !== '.' && $file !== '..') {

                        if(is_dir($path . DIRECTORY_SEPARATOR . $file)) {

                            $files[$file] = call_user_func_array([$this,'loadEmoji'],[$path . DIRECTORY_SEPARATOR . $file]);
                        } else {
                            preg_match('/\.(gif|jpg|png)/i', $file) && $files[] = $path . DIRECTORY_SEPARATOR . $file;
                        }
                    }
                }
                closedir($dirs);
            }
        }
        return $files;
    }

}
