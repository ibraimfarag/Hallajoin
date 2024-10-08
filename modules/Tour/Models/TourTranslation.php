<?php
namespace Modules\Tour\Models;

use App\BaseModel;

class TourTranslation extends BaseModel
{
    protected $table = 'bravo_tour_translations';
    protected $fillable = [
        'title',
        'content',
        'short_desc',
        'address',
        'faqs',
        'include',
        'other',
        'exclude',
        'sections',
        'packages',
        'itinerary',
        'surrounding',
    ];
    protected $slugField     = false;
    protected $seo_type = 'tour_translation';
    protected $cleanFields = [
        'content'
    ];
    protected $casts = [
        'faqs' => 'array',
        'include' => 'array',
        'other' => 'array',
        'exclude' => 'array',
        'sections' => 'array',
        'packages' => 'array',
        'itinerary' => 'array',
        'surrounding' => 'array',
    ];
    public function getSeoType(){
        return $this->seo_type;
    }
    public function getRecordRoot(){
        return $this->belongsTo(Tour::class,'origin_id');
    }
}