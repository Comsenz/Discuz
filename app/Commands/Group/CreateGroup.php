<?php


namespace App\Commands\Group;


use App\Models\Group;
use App\Models\User;
use App\Validators\GroupValidator;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class CreateGroup
{
    use AssertPermissionTrait;

    protected $user;
    protected $data;
    protected $groupValidator;

    public function __construct(User $user, Collection $data)
    {
        $this->user = $user;
        $this->data = $data;
    }


    /**
     * @param GroupValidator $groupValidator
     * @return Group
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function handle(GroupValidator $groupValidator)
    {
        $this->assertCan($this->user, 'createGroup');

        $group = new Group();

        $data = Arr::get($this->data, 'data.attributes', []);

        $group->name = Arr::get($data, 'name');
        $group->type = Arr::get($data, 'type');
        $group->color = Arr::get($data, 'color');
        $group->icon = Arr::get($data, 'icon');

        $groupValidator->valid($group->getAttributes());
        $group->save();

        return $group;
    }

}
