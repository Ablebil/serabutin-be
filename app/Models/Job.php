<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    /**
     * The column used for soft deletes.
     *
     * @var string
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'category_id',
        'title',
        'description',
        'budget_min',
        'budget_max',
        'workers_needed',
        'location_district',
        'location_city',
        'status',
        'start_at',
        'deadline_at',
    ];

    /**
     * Indicates if the model should be auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the primary key.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'budget_min'     => 'decimal:2',
            'budget_max'     => 'decimal:2',
            'workers_needed' => 'integer',
            'start_at'       => 'datetime',
            'deadline_at'    => 'datetime',
        ];
    }

    /**
     * Get the client who posted this job.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get the category of this job.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
