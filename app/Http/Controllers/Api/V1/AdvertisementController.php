<?php
namespace App\Http\Controllers\Api\V1;

use App\Models\Advertisement;
use App\Http\Controllers\Controller;
use App\CentralLogics\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdvertisementController extends Controller
{
    public function get_adds()
    {
        $Advertisement= Advertisement::valid()
        ->when(config('module.current_module_data'), function($query){
            $query->where('module_id', config('module.current_module_data')['id']);
        })
        ->with('store')->orderByRaw('ISNULL(priority), priority ASC')
        ->get();

        try {
            $Advertisement->each(function ($advertisement) {
                $advertisement->reviews_comments_count = (int) $advertisement?->store?->reviews_comments()->count();
                $reviewsInfo = $advertisement?->store?->reviews()
                ->selectRaw('avg(reviews.rating) as average_rating, count(reviews.id) as total_reviews, items.store_id')
                ->groupBy('items.store_id')
                ->first();

                $advertisement->average_rating = (float)  $reviewsInfo?->average_rating ?? 0;
                // unset($advertisement->store);
            });
        } catch (\Exception $e) {
            info($e->getMessage());
        }

        return response()->json($Advertisement, 200);
    }

}
