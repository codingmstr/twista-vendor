<?php

namespace App\CentralLogics;

use App\Models\Banner;
use App\Models\Item;
use App\Models\Store;
use App\CentralLogics\Helpers;

class BannerLogic
{
    public static function get_banners($zone_id, $featured = false,$is_main = false)
    {
        $banners = Banner::active()
        ->when($featured, function($query){
            $query->featured();
        })
        ->when($is_main, function($query){
            $query->where('is_main',1);
        })
        ;
        if(config('module.current_module_data')) {
            $banners = $banners->whereHas('zone.modules', function($query){
                $query->where('modules.id', config('module.current_module_data')['id']);
            })
            ->module(config('module.current_module_data')['id'])
            ->when(!config('module.current_module_data')['all_zone_service'], function($query)use($zone_id){
                $query->whereIn('zone_id', json_decode($zone_id, true));
            });
        }

        $banners = $banners->whereIn('zone_id', json_decode($zone_id, true))
        ->orWhere(function($q){
            $q->whereNull('module_id')->whereNull('zone_id');
            })
        /*->whereHas('module',function($query){
            $query->active();
        })*/->where('created_by','admin')
        ->get();

        $data = [];
        foreach($banners as $banner)
        {
            if($banner->type=='store_wise')
            {
                $store = Store::active()
                ->when(config('module.current_module_data'), function($query){
                    $query->whereHas('zone.modules', function($query){
                        $query->where('modules.id', config('module.current_module_data')['id']);
                    });
                })
                ->find($banner->data);
                $data[]=[
                    'id'=>$banner->id,
                    'is_main'=> $banner->is_main,
                    'module_id'=>$banner->module_id,
                    'title'=>$banner->title,
                    'sub_title'=>$banner->sub_title,
                    'type'=>$banner->type,
                    'image'=>$banner->image,
                    'bg_color'=>$banner->bg_color,
                    'link'=> null,
                    'store'=> $store?Helpers::store_data_formatting($store, false):null,
                    'item'=>null
                ];
            }
            if($banner->type=='item_wise')
            {
                $item = Item::active()
                ->when(config('module.current_module_data'), function($query)use($zone_id) {
                    $query->whereHas('module.zones',function($query)use($zone_id){
                        $query->whereIn('zones.id', json_decode($zone_id, true))->orWhereNull('zone_id');
                    });
                })
                ->find($banner->data);
                $data[]=[
                    'id'=>$banner->id,
                    'is_main'=> $banner->is_main,
                    'module_id'=>$banner->module_id,
                    'title'=>$banner->title,
                    'sub_title'=>$banner->sub_title,
                    'type'=>$banner->type,
                    'image'=>$banner->image,
                    'bg_color'=>$banner->bg_color,
                    'link'=> null,
                    'store'=> null,
                    'item'=> $item?Helpers::product_data_formatting($item, false, false, app()->getLocale()):null,
                ];
            }
            if($banner->type=='default')
            {
                $data[]=[
                    'id'=>$banner->id,
                    'is_main'=> $banner->is_main,
                    'module_id'=>$banner->module_id,
                    'title'=>$banner->title,
                    'sub_title'=>$banner->sub_title,
                    'type'=>$banner->type,
                    'image'=>$banner->image,
                    'bg_color'=>$banner->bg_color,
                    'link'=>$banner->default_link,
                    'store'=> null,
                    'item'=> null,
                ];
            }
            if($banner->type == null)
            {
                $data[]=[
                    'id'=>$banner->id,
                    'is_main'=> $banner->is_main,
                    'module_id'=>$banner->module_id,
                    'title'=>$banner->title,
                    'sub_title'=>$banner->sub_title,
                    'type'=>$banner->type,
                    'image'=>$banner->image,
                    'bg_color'=>$banner->bg_color,
                    'link'=> null,
                    'store'=> null,
                    'item'=> null,
                ];
            }
        }
        return $data;
    }
}
