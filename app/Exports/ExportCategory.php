<?php

namespace App\Exports;

use App\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class ExportCategory implements FromCollection, WithHeadings
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // $collection = new Collection
        $categories_arranged = [];
        $categories = Category::where('type','Category')->with('subCategoriesWithSubSub')->get()->toArray();
        // $categories2[] = $categories[0];
        $count = 0;
        foreach($categories as $a => $b){
            $count ++;
            $item = $b['name']; //bags and travel
            if(isset($b['sub_categories_with_sub_sub']) && !empty($b['sub_categories_with_sub_sub'])){
                foreach($b['sub_categories_with_sub_sub'] as $c => $d){
                    $count ++;
                    $item = $count.'/'.$b['name']; //bags and travel
                    $item .= '/'.$d['name']; //bags and travel/travel
                    $temp_1 = $item;
                    if(isset($d['subsub_categories_with_sub_sub_sub']) && !empty($d['subsub_categories_with_sub_sub_sub'])){
                        foreach($d['subsub_categories_with_sub_sub_sub'] as $e => $f){
                            $count ++;
                            $item .= '/'.$f['name'];//bags and travel/travel/laptop bags
                            $temp_2 = $item;
                            if(isset($f['subsubsub_categories_with_sub_sub_sub_sub']) && !empty($f['subsubsub_categories_with_sub_sub_sub_sub'])){
                                foreach($f['subsubsub_categories_with_sub_sub_sub_sub'] as $g => $h){
                                    $count ++;
                                    $item .= '/'.$h['name'];//bags and travel/travel/laptop bags/briefcase
                                    $temp_3 = $item;
                                    if(isset($h['subsubsubsub_categories_with_sub_sub']) && !empty($h['subsubsubsub_categories_with_sub_sub'])){
                                        foreach($h['subsubsubsub_categories_with_sub_sub'] as $i => $j){
                                            $count ++;
                                            $item .= '/'.$h['name'];
                                        }
                                    }else{
                                        $categories_arranged[] = [
                                            'category' => $item,
                                            'category url' => $h['slug'],
                                            'category seo title' => $h['meta_title'],
                                            'category seo description' => $h['meta_description']
                                        ];
                                        $item = $temp_2;
                                    }
                                }
                            }else{
                                $categories_arranged[] = [
                                    'category' => $item,
                                    'category url' => $f['slug'],
                                    'category seo title' => $f['meta_title'],
                                    'category seo description' => $f['meta_description']
                                ];
                                $item = $temp_1;

                            }
                        }
                    }else{                        
                        $categories_arranged[] = [
                            'category' => $item,
                            'category url' => $d['slug'],
                            'category seo title' => $d['meta_title'],
                            'category seo description' => $d['meta_description']
                        ];
                        $item = $item;
                    }
                }
            }else{
                $categories_arranged[] = [
                    'category' => $item,
                    'category url' => $b['slug'],
                    'category seo title' => $b['meta_title'],
                    'category seo description' => $b['meta_description']
                ];
                $item = $item;
            }
            // $categories_arranged[] = [
            //     'category' => $item,
            //     'slug' => $b['slug']
            // ];
            $item = '';
        }
        return collect((object) $categories_arranged);
        // return Category::select('name','slug','meta_title','meta_description')->orderBy('id','desc')->get();
        // return Category::all();
    }
    public function headings(): array
    {
        return ["Category", "Category Url", "Category Seo title", "Category Seo Description"];
    }
}
