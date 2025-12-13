<?php

namespace Modules\Report\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class SearchHistory extends Model
{
    protected $table = 'core_search_history';

    protected $fillable = [
        'keyword',
        'user_ip',
        'user_id',
        'platform',
        'is_operator',
        'from_b2b',
        'device_type',
        'browser',
        'user_agent',
    ];

    protected $casts = [
        'is_operator' => 'boolean',
        'from_b2b' => 'boolean',
    ];

    /**
     * Get the user that performed the search.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
