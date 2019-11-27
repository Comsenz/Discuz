<?php

namespace App\Commands\Emoji;

use App\Events\Group\Saving;
use App\Models\Emoji;
use App\Models\Group;
use App\Models\User;
use App\Validators\GroupValidator;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\NotAuthenticatedException;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class LoadEmoji
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    const EMOJI_PATH_NAME = 'emoji';

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
                $data = [];
                foreach ($emojies as $category => $emojies_data){
                    //todo 去重过滤
                    foreach ($emojies_data as $emojy){
                        $data[] = ['url' => $emojy, 'category' => $category, 'created_at'=>date('Y-m-d H:i:s', time()), 'updated_at'=>date('Y-m-d H:i:s', time())];
                    }
                    Emoji::insert($data);
                }
            }
        }else{
            if (is_dir('emoji'.DIRECTORY_SEPARATOR.$this->category)){

                $emojies = $this->loadEmoji('emoji'.DIRECTORY_SEPARATOR.$this->category);

                foreach ($emojies as $emojy){
                    $data[] = ['url' => $emojy, 'category' => $this->category, 'created_at'=>date('Y-m-d H:i:s', time()), 'updated_at'=>date('Y-m-d H:i:s', time())];
                }
                Emoji::insert($data);
            }
        }
    }

    /**
     * @param $path
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
