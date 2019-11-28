<?php


namespace App\Api\Serializer;


use App\Models\User;
use Discuz\Api\Serializer\AbstractSerializer;
use Discuz\Contracts\Setting\SettingsRepository;
use Illuminate\Support\Arr;

class ForumSettingSerializer extends AbstractSerializer
{

    protected $type = 'forum';

    protected $settings;

    public function __construct(SettingsRepository $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Get the default set of serialized attributes for a model.
     *
     * @param object|array $model
     * @return array
     */
    public function getDefaultAttributes($model)
    {
        $attributes = [
            'siteMode' => $this->settings->get('site.mode'),
            'logo' => $this->settings->get('site.logo'),
            'siteName' => $this->settings->get('site.name'),
            'siteIntroduction' => $this->settings->get('site.introduction'),
            'siteInstall' => $this->settings->get('site.install'),
            'threads' => 0,
            'members' => 0,
            'siteAuthor' => $this->settings->get('site.author')
//            'users' => $this->settings->get('site.name'),
//            'siteName' => $this->settings->get('site.name'),
//            'siteName' => $this->settings->get('site.name'),
        ];

        if($this->actor->exists) {
            $attributes['user'] = [
                'groups' => $this->actor->groups,
                'registerTime' => $this->formatDate($this->actor->created_at),
            ];
        }



        return $attributes;
    }

    public function getId($model)
    {
        return 1;
    }

    protected function users($model) {

        return $this->hasMany($model, UserSerializer::class);
    }
}
