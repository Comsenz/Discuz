<?php


namespace App\Api\Serializer;


use App\Models\GroupPermission;
use Discuz\Api\Serializer\AbstractSerializer;
use Illuminate\Contracts\Container\BindingResolutionException;
use Tobscure\JsonApi\Relationship;

class GroupSerializer extends AbstractSerializer
{

    protected $type = 'groups';

    /**
     * Get the default set of serialized attributes for a model.
     *
     * @param object|array $model
     * @return array
     */
    public function getDefaultAttributes($model)
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'type' => $model->type,
            'color' => $model->color,
            'icon' => $model->icon
        ];
    }

    /**
     * @param $group
     * @return Relationship
     * @throws BindingResolutionException
     */
    public function groupPermission($group)
    {
        return $this->hasMany($group, GroupPermissionSerializer::class, 'groupPermission');
    }

}
