<?php
namespace Modules\Tour\Blocks;

use Modules\Media\Helpers\FileHelper;
use Modules\Template\Blocks\BaseBlock;
use Modules\Review\Models\Review;
use Carbon\Carbon;

class Testimonial extends BaseBlock
{
    public function getOptions(){
        return [
            'settings' => [
                [
                    'id'        => 'title',
                    'type'      => 'input',
                    'inputType' => 'text',
                    'label'     => __('Title')
                ],
                [
                    'id'          => 'list_item',
                    'type'        => 'listItem',
                    'label'       => __('List Item(s)'),
                    'title_field' => 'title',
                    'settings'    => [
                        [
                            'id'        => 'name',
                            'type'      => 'input',
                            'inputType' => 'text',
                            'label'     => __('Name')
                        ],
                        [
                            'id'    => 'desc',
                            'type'  => 'textArea',
                            'label' => __('Desc')
                        ],
                        [
                            'id'        => 'number_star',
                            'type'      => 'input',
                            'inputType' => 'number',
                            'label'     => __('Number star')
                        ],
                        [
                            'id'    => 'avatar',
                            'type'  => 'uploader',
                            'label' => __('Avatar Image')
                        ],
                        [
                            'id'           => 'custom_ids',
                            'type'         => 'select2',
                            'label'        => __('List by IDs'),
                            'select2'      => [
                                'ajax'     => [
                                    'url'      => route('tour.admin.getForSelect2'),
                                    'dataType' => 'json'
                                ],
                                'width'    => '100%',
                                'multiple' => "true",
                                'placeholder' => __('-- Select --')
                            ],
                            'pre_selected' => route('tour.admin.getForSelect2', [
                                'pre_selected' => 1
                            ])
                        ],
                    ]
                ],
            ],
            'category'=>__("Other Block")
        ];
    }

    public function getName()
    {
        return __('List Testimonial');
    }

    public function content($model = [])
    {


        $reviews = Review::with(['tour' => function ($query) {
            $query->withCount('reviews');
        }, 'reviewer'])
        ->where('future', 1)
        ->get();
        $formattedReviews = $reviews->map(function ($review) {
            $review ['date']= Carbon::parse($review['created_at'])->format('F Y');
            return $review;
        });
    
        $model['reviews'] = $formattedReviews->toArray();
    
        // dd($model);
        
        return view('Tour::frontend.blocks.testimonial.index', $model);
    }

    public function contentAPI($model = []){
        if(!empty($model['list_item'])){
            foreach (  $model['list_item'] as &$item ){
                $item['avatar_url'] = FileHelper::url($item['avatar'], 'full');
            }
        }
        return $model;
    }
}
