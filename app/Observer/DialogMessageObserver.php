<?php


namespace App\Observer;


use App\Models\Attachment;
use App\Models\DialogMessage;
use Discuz\Contracts\Setting\SettingsRepository;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;

class DialogMessageObserver
{
    protected $settings;

    protected $request;

    public function __construct(SettingsRepository $settings, ServerRequestInterface $request)
    {
        $this->settings = $settings;
        $this->request = $request;
    }

    /**
     *
     * @param DialogMessage $dialogMessage
     * @return void
     */
    public function created(DialogMessage $dialogMessage)
    {
        $attachments = Arr::get($this->request->getParsedBody(), 'relationships.attachments.data');

        //更新附件的type_id
        /** @var Attachment $images */
        $images = $attachments && Attachment::query()
                ->where('user_id', $dialogMessage->user_id)
                ->where('type_id', 0)
                ->where('type', Attachment::TYPE_OF_DIALOG_MESSAGE)
                ->whereIn('id', array_column($attachments, 'id'))
                ->first();

        if (!$images) {
            throw new \Exception('attachments_error');
        }

        $images->update(['type_id' => $dialogMessage->id]);
    }
}
