<?php


namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @package App\Models
 *
 * @property int $id
 * @property int $question_id
 * @property int $user_id
 * @property int $order_id
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class QuestionOnlooker extends Model
{
    protected $fillable = [
        'question_id',
        'user_id',
        'order_id',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [];

    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * Create a new self
     *
     * @param array $attributes
     * @return static
     */
    public static function build(array $attributes)
    {
        $self = new static;

        $self->fill($attributes);

        return $self;
    }

    /**
     * Define the relationship with the QuestionOnlooker's author.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
