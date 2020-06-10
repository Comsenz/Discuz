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
        $attachment_id = Arr::get($this->request->getParsedBody(), 'data.attributes.attachment_id');

        //更新附件的type_id
        if ($attachment_id) {
            $attachments = Attachment::query()
                    ->where('user_id', $dialogMessage->user_id)
                    ->where('type_id', 0)
                    ->where('type', Attachment::TYPE_OF_DIALOG_MESSAGE)
                    ->where('id', $attachment_id)
                    ->first();

            if (!$attachments) {
                throw new \Exception('attachments_error');
            }
            $attachments->type_id = $dialogMessage->id;
            $attachments->save();
        }
    }
}
