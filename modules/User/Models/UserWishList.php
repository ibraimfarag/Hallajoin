<?php
namespace Modules\User\Models;
use App\BaseModel;

class UserWishList extends BaseModel
{
    protected $table = 'user_wishlist';
    protected $fillable = [
        'object_id',
        'object_model',
        'user_id'
    ];

    public function service()
    {
        $allServices = get_bookable_services();
        $objectModel = $this->object_model ?: 'tour';

        $module = $allServices[$objectModel];
        // dd( $module);
        return $this->hasOne($module, "id", 'object_id')->where("deleted_at",null);
    }
}