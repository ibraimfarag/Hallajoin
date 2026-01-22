<?php

namespace Modules\Booking\Models;

use App\BaseModel;
use App\User;

class EnquiryReply extends BaseModel
{
    protected $table = 'bravo_enquiry_replies';

    protected $fillable = [
        'parent_id',
        'user_id',
        'content',
        'attachment',
        'create_user',
        'update_user',
    ];

    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class, 'parent_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }
}
