<?php

namespace App;

use App\Product;
use App\Category;
use App\SubCategory;
use App\SubSubCategory;
use App\SubSubSubCategory;
use App\SubSubSubSubCategory;
use App\User;
// use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Auth;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CategoriesImport implements ToCollection, WithHeadingRow, WithValidation,SkipsOnFailure 
{
    public function onFailure(Failure ...$failures)
    {
        // Handle the failures how you'd like.
    }
    // WithHeadingRow
    public function collection(Collection $row)
    {    
        set_time_limit(300);     
        $cat = '';
        $sub_cat = '';
        $sub_sub_cat = '';
        $sub_sub_sub_cat = '';
        $sub_sub_sub_sub_cat = '';
        $sub_sub_sub_sub_sub_cat = '';
        $sub_sub_sub_sub_sub_sub_cat = '';
        
        try{          
                $count = 0;
                $count2 = 0;
            foreach($row as $c => $d){  
                $count++;
                $meta = [
                    'type' => '',
                    'id' => ''
                ];     
                foreach($d as $a => $b){ 
                    $count2++;
                    // echo $count.'<br>';
                    // echo $b.'<br>';
                    if($a == 'category'){
                        $explode = explode('/',$b);

                        if (isset($explode[1]) && !empty($explode[1])) {
                            // echo 'A<br>';
                            $cat = $explode['1'];
                            if(Category::where('type','Category')->where('name',trim(trim(str_replace("'", "", $cat))))->count() == 0){
                                
                                $a=trim(strtolower($cat));
                                $b=preg_replace('/[^a-z0-9 -]+/', '', $a);
                                $c=str_replace(' ', '-', $b);
                                $d=str_replace('--','-',$c);
                            
                                $cat_upload = Category::create([
                                    'name' => trim(trim(str_replace("'", "", $cat))),
                                    'slug' => $d.Str::random(5),
                                    'type' => 'Category',
                                    // 'meta_title' => trim(trim(str_replace("'", "", $cat))),
                                    // 'meta_description' => trim(trim(str_replace("'", "", $cat)))
                                ]);
                            }
                            
                            $cat_2 = Category::where('type','Category')->where('name',(trim(str_replace("'", "", $cat))))->first();
                            // try{
                            //     echo 'Cat Id is '.$cat_2->id;
                            // }catch(Exception $e){
                            //     dd('asdf',$e->getMessage());
                            // }
                            $meta = [
                                'type' => 'Category',
                                'id' => $cat_2->id
                            ]; 
                        }
                        if (isset($explode[2]) && !empty($explode[2])) {
                            $sub_cat = $explode[2];
                            // echo 'B<br>';
                            if(Category::where('type','SubCategory')->where(['name' => (trim(str_replace("'", "", $sub_cat))),'parent' => $cat_2->id])->count() == 0){
                                $a=trim(strtolower($sub_cat));
                                $b=preg_replace('/[^a-z0-9 -]+/', '', $a);
                                $c=str_replace(' ', '-', $b);
                                $d=str_replace('--','-',$c);
                                // $cat_upload = Category::where('name',(trim(str_replace("'", "", $cat))))->first();
                                // $count_url = Category::where('slug',$d)->count();
                                $sub_cat_upload = Category::create([
                                    'name' => (trim(str_replace("'", "", $sub_cat))),
                                    'parent' =>  $cat_2->id,
                                    'slug' => $d.Str::random(5),
                                    'type' => 'SubCategory'
                                    // 'meta_title' => (trim(str_replace("'", "", $sub_cat))),
                                    // 'meta_description' => (trim(str_replace("'", "", $sub_cat)))
                                ]);
                            }
                            $sub_cat_2 = Category::where('type','SubCategory')->where(['name' => (trim(str_replace("'", "", $sub_cat))),'parent' => $cat_2->id])->first();
                            $meta = [
                                'type' => 'SubCategory',
                                'category_id' =>  $cat_2->id,
                                'id' => $sub_cat_2->id
                            ];
                            // else{
                            //     $sub_cat_upload = SubCategory::where('name',$sub_cat)->first();
                            // }
                        }
                        if (isset($explode[3]) && !empty($explode[3])) {
                            // echo 'C<br>';
                            $sub_sub_cat = $explode[3];
                            if(Category::where('type','SubSubCategory')->where(['name' => (trim(str_replace("'", "", $sub_sub_cat))),'parent' => $sub_cat_2->id])->count() == 0){
                                $a=trim(strtolower($sub_sub_cat));
                                $b=preg_replace('/[^a-z0-9 -]+/', '', $a);
                                $c=str_replace(' ', '-', $b);
                                $d=str_replace('--','-',$c);
                                // $sub_cat_upload = Category::where('id',$sub_cat_2->id)->first();
                                // $count_url = Category::where('slug',$d)->count();
                                $sub_cat_upload = Category::create([
                                    'name' => (trim(str_replace("'", "", $sub_sub_cat))),
                                    'parent' =>  $sub_cat_2->id,
                                    'slug' => $d.Str::random(5),
                                    'type' => 'SubSubCategory',
                                    // 'meta_title' => (trim(str_replace("'", "", $sub_sub_cat))),
                                    // 'meta_description' => (trim(str_replace("'", "", $sub_sub_cat)))
                                ]);
                            }
                            
                            $sub_sub_cat_2 = Category::where('type','SubSubCategory')->where(['name' => (trim(str_replace("'", "", $sub_sub_cat))),'parent' => $sub_cat_2->id])->first();
                            $meta = [
                                'type' => 'SubSubCategory',
                                'sub_category_id' => $sub_cat_2->id,
                                'id' => $sub_sub_cat_2->id
                            ];
                            // else{
                            //     $sub_cat_upload = SubSubCategory::where('name',$sub_sub_cat)->first();
                            // }
                        }
                        if (isset($explode[4]) && !empty($explode[4])) {
                            // echo 'D<br>';
                            $sub_sub_sub_cat = $explode[4];
                            if(Category::where('type','SubSubSubCategory')->where(['name' => (trim(str_replace("'", "", $sub_sub_sub_cat))),'parent' => $sub_sub_cat_2->id])->count() == 0){
                                $a=trim(strtolower($sub_sub_sub_cat));
                                $b=preg_replace('/[^a-z0-9 -]+/', '', $a);
                                $c=str_replace(' ', '-', $b);
                                $d=str_replace('--','-',$c);
                                // $sub_cat_upload = Category::where('id',$sub_sub_cat_2->id)->first();
                                // $count_url = Category::where('slug',$d)->count();
                                $sub_cat_upload = Category::create([
                                    'name' => (trim(str_replace("'", "", $sub_sub_sub_cat))),
                                    'parent' =>  $sub_sub_cat_2->id,
                                    'slug' => $d.Str::random(5),
                                    'type' => 'SubSubSubCategory',
                                    // 'meta_title' => (trim(str_replace("'", "", $sub_sub_sub_cat))),
                                    // 'meta_description' => (trim(str_replace("'", "", $sub_sub_sub_cat)))
                                ]);
                            }
                            
                            $sub_sub_sub_cat_2 = Category::where('type','SubSubSubCategory')->where(['name' => (trim(str_replace("'", "", $sub_sub_sub_cat))),'parent' => $sub_sub_cat_2->id])->first();
                            $meta = [
                                'type' => 'SubSubSubCategory',
                                'sub_sub_category_id' => $sub_sub_cat_2->id,
                                'id' => $sub_sub_sub_cat_2->id
                            ];
                            // else{
                            //     $sub_cat_upload = SubSubSubCategory::where('name',$sub_sub_sub_cat)->first();
                            // }
                        }
                        if (isset($explode[5]) && !empty($explode[5])) {
                            // echo 'E<br>';
                            $sub_sub_sub_sub_cat = $explode[5];
                            if(Category::where('type','SubSubSubSubCategory')->where(['name' => (trim(str_replace("'", "", $sub_sub_sub_sub_cat))),'parent' => $sub_sub_sub_cat_2->id])->count() == 0){
                                $a=trim(strtolower($sub_sub_sub_sub_cat));
                                $b=preg_replace('/[^a-z0-9 -]+/', '', $a);
                                $c=str_replace(' ', '-', $b);
                                $d=str_replace('--','-',$c);
                                // $sub_cat_upload = Category::where('id',$sub_sub_sub_cat_2->id)->first();
                                // $count_url = Category::where('slug',$d)->count();
                                $sub_cat_upload = Category::create([
                                    'name' => (trim(str_replace("'", "", $sub_sub_sub_sub_cat))),
                                    'parent' =>  $sub_sub_sub_cat_2->id,
                                    'slug' => $d.Str::random(5),
                                    'type' => 'SubSubSubSubCategory',
                                    // 'meta_title' => (trim(str_replace("'", "", $sub_sub_sub_sub_cat))),
                                    // 'meta_description' => (trim(str_replace("'", "", $sub_sub_sub_sub_cat)))
                                ]);
                            }
                            
                            $sub_sub_sub_sub_cat_2 = Category::where('type','SubSubSubSubCategory')->where(['name' => (trim(str_replace("'", "", $sub_sub_sub_sub_cat))),'parent' => $sub_sub_sub_cat_2->id])->first();
                            
                            $meta = [
                                'type' => 'SubSubSubSubCategory',
                                'sub_sub_sub_category_id' => $sub_sub_sub_cat_2->id,
                                'id' => $sub_sub_sub_sub_cat_2->id
                            ];
                            // else{
                            //     $sub_cat_upload = SubSubSubSubCategory::where('name',$sub_sub_sub_sub_cat)->first();
                            // }
                        }
                        if (isset($explode[6]) && !empty($explode[6])) {
                            // echo 'E<br>';
                            $sub_sub_sub_sub_sub_cat = $explode[6];
                            if(Category::where('type','SubSubSubSubSubCategory')->where(['name' => (trim(str_replace("'", "", $sub_sub_sub_sub_sub_cat))),'parent' => $sub_sub_sub_sub_cat_2->id])->count() == 0){
                                $a=trim(strtolower($sub_sub_sub_sub_sub_cat));
                                $b=preg_replace('/[^a-z0-9 -]+/', '', $a);
                                $c=str_replace(' ', '-', $b);
                                $d=str_replace('--','-',$c);
                                // $sub_cat_upload = Category::where('id',$sub_sub_sub_cat_2->id)->first();
                                // $count_url = Category::where('slug',$d)->count();
                                $sub_cat_upload = Category::create([
                                    'name' => (trim(str_replace("'", "", $sub_sub_sub_sub_sub_cat))),
                                    'parent' =>  $sub_sub_sub_sub_cat_2->id,
                                    'slug' => $d.Str::random(5),
                                    'type' => 'SubSubSubSubSubCategory',
                                    // 'meta_title' => (trim(str_replace("'", "", $sub_sub_sub_sub_cat))),
                                    // 'meta_description' => (trim(str_replace("'", "", $sub_sub_sub_sub_cat)))
                                ]);
                            }
                            
                            $sub_sub_sub_sub_sub_cat_2 = Category::where('type','SubSubSubSubSubCategory')->where(['name' => (trim(str_replace("'", "", $sub_sub_sub_sub_sub_cat))),'parent' => $sub_sub_sub_sub_cat_2->id])->first();
                            
                            $meta = [
                                'type' => 'SubSubSubSubSubCategory',
                                'sub_sub_sub_sub_category_id' => $sub_sub_sub_sub_cat_2->id,
                                'id' => $sub_sub_sub_sub_sub_cat_2->id
                            ];
                            // else{
                            //     $sub_cat_upload = SubSubSubSubCategory::where('name',$sub_sub_sub_sub_cat)->first();
                            // }
                        }
                        if (isset($explode[7]) && !empty($explode[7])) {
                            // echo 'E<br>';
                            $sub_sub_sub_sub_sub_sub_cat = $explode[7];
                            if(Category::where('type','SubSubSubSubSubSubCategory')->where(['name' => (trim(str_replace("'", "", $sub_sub_sub_sub_sub_sub_cat))),'parent' => $sub_sub_sub_sub_sub_cat_2->id])->count() == 0){
                                $a=trim(strtolower($sub_sub_sub_sub_sub_sub_cat));
                                $b=preg_replace('/[^a-z0-9 -]+/', '', $a);
                                $c=str_replace(' ', '-', $b);
                                $d=str_replace('--','-',$c);
                                // $sub_cat_upload = Category::where('id',$sub_sub_sub_cat_2->id)->first();
                                // $count_url = Category::where('slug',$d)->count();
                                $sub_cat_upload = Category::create([
                                    'name' => (trim(str_replace("'", "", $sub_sub_sub_sub_sub_sub_cat))),
                                    'parent' =>  $sub_sub_sub_sub_sub_cat_2->id,
                                    'slug' => $d.Str::random(5),
                                    'type' => 'SubSubSubSubSubSubCategory',
                                    // 'meta_title' => (trim(str_replace("'", "", $sub_sub_sub_sub_cat))),
                                    // 'meta_description' => (trim(str_replace("'", "", $sub_sub_sub_sub_cat)))
                                ]);
                            }
                            
                            $sub_sub_sub_sub_sub_cat_2 = Category::where('type','SubSubSubSubSubSubCategory')->where(['name' => (trim(str_replace("'", "", $sub_sub_sub_sub_sub_sub_cat))),'parent' => $sub_sub_sub_sub_sub_cat_2->id])->first();
                            
                            $meta = [
                                'type' => 'SubSubSubSubSubSubCategory',
                                'sub_sub_sub_sub_sub_category_id' => $sub_sub_sub_sub_sub_cat_2->id,
                                'id' => $sub_sub_sub_sub_sub_cat_2->id
                            ];
                            // else{
                            //     $sub_cat_upload = SubSubSubSubCategory::where('name',$sub_sub_sub_sub_cat)->first();
                            // }
                        }
                    }
                    
                    if($a == 'category_seo_title'){
                        if($b != ''){
                            if($meta['type'] != ''){
                                if($meta['type'] == 'Category'){
                                    if($meta['id']  > 0){
                                        $sub = Category::where('id',$meta['id'])->update([
                                            'meta_title' => $b
                                        ]);
                                    }
                                }
                                if($meta['type'] == 'SubCategory'){
                                    if($meta['id']  > 0){
                                        $sub = Category::where(['id' => $meta['id'],'parent' => $meta['category_id']])->update([
                                            'meta_title' => $b
                                        ]);
                                    }
                                }
                                if($meta['type'] == 'SubSubCategory'){
                                    if($meta['id']  > 0){
                                        $sub = Category::where(['id' => $meta['id'],'parent' => $meta['sub_category_id']])->update([
                                            'meta_title' => $b
                                        ]);
                                    }       
                                }
                                if($meta['type'] == 'SubSubSubCategory'){
                                    if($meta['id']  > 0){
                                        $sub = Category::where(['id' => $meta['id'],'parent' => $meta['sub_sub_category_id']])->update([
                                            'meta_title' => $b
                                        ]);
                                    }
                                }
                                if($meta['type'] == 'SubSubSubSubCategory'){
                                    if($meta['id']  > 0){
                                        $sub = Category::where(['id' => $meta['id'],'parent' => $meta['sub_sub_sub_category_id']])->update([
                                            'meta_title' => $b
                                        ]);
                                    }
                                }
                                if($meta['type'] == 'SubSubSubSubSubCategory'){
                                    if($meta['id']  > 0){
                                        $sub = Category::where(['id' => $meta['id'],'parent' => $meta['sub_sub_sub_sub_category_id']])->update([
                                            'meta_title' => $b
                                        ]);
                                    }
                                }
                                if($meta['type'] == 'SubSubSubSubSubSubCategory'){
                                    if($meta['id']  > 0){
                                        $sub = Category::where(['id' => $meta['id'],'parent' => $meta['sub_sub_sub_sub_sub_category_id']])->update([
                                            'meta_title' => $b
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                    if($a == 'category_seo_description'){
                        if($b != ''){
                            if($meta['type'] != ''){
                                if($meta['type'] == 'Category'){
                                    if($meta['id']  > 0){
                                        $sub = Category::where('id',$meta['id'])->update([
                                            'meta_description' => $b
                                        ]);
                                    }
                                }
                                if($meta['type'] == 'SubCategory'){
                                    if($meta['id']  > 0){
                                        $sub = Category::where(['id' => $meta['id'],'parent' => $meta['category_id']])->update([
                                            'meta_description' => $b
                                        ]);
                                    }
                                }
                                if($meta['type'] == 'SubSubCategory'){
                                    if($meta['id']  > 0){
                                        $sub = Category::where(['id' => $meta['id'],'parent' => $meta['sub_category_id']])->update([
                                            'meta_description' => $b
                                        ]);
                                    }
                                }
                                if($meta['type'] == 'SubSubSubCategory'){
                                    if($meta['id']  > 0){
                                        $sub = Category::where(['id' => $meta['id'],'parent' => $meta['sub_sub_category_id']])->update([
                                            'meta_description' => $b
                                        ]);
                                    }
                                }
                                if($meta['type'] == 'SubSubSubSubCategory'){
                                    if($meta['id']  > 0){
                                        $sub = Category::where(['id' => $meta['id'],'parent' => $meta['sub_sub_sub_category_id']])->update([
                                            'meta_description' => $b
                                        ]);
                                    }
                                }
                                if($meta['type'] == 'SubSubSubSubSubCategory'){
                                    if($meta['id']  > 0){
                                        $sub = Category::where(['id' => $meta['id'],'parent' => $meta['sub_sub_sub_sub_category_id']])->update([
                                            'meta_description' => $b
                                        ]);
                                    }
                                }
                                if($meta['type'] == 'SubSubSubSubSubSubCategory'){
                                    if($meta['id']  > 0){
                                        $sub = Category::where(['id' => $meta['id'],'parent' => $meta['sub_sub_sub_sub_sub_category_id']])->update([
                                            'meta_description' => $b
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                    if($a == 'category_url'){
                        if($b != ''){
                            if($meta['type'] != ''){
                                if($meta['type'] == 'Category'){
                                    if($meta['id']  > 0){
                                        $sub = Category::where('id',$meta['id'])->update([
                                            'slug' => $b
                                        ]);
                                    }
                                }
                                if($meta['type'] == 'SubCategory'){
                                    if($meta['id']  > 0){
                                        $sub = Category::where(['id' => $meta['id'],'parent' => $meta['category_id']])->update([
                                            'slug' => $b
                                        ]);
                                    }
                                }
                                if($meta['type'] == 'SubSubCategory'){
                                    if($meta['id']  > 0){
                                        $sub = Category::where(['id' => $meta['id'],'parent' => $meta['sub_category_id']])->update([
                                            'slug' => $b
                                        ]);
                                    }
                                }
                                if($meta['type'] == 'SubSubSubCategory'){
                                    if($meta['id']  > 0){
                                        $sub = Category::where(['id' => $meta['id'],'parent' => $meta['sub_sub_category_id']])->update([
                                            'slug' => $b
                                        ]);
                                    }
                                }
                                if($meta['type'] == 'SubSubSubSubCategory'){
                                    if($meta['id']  > 0){
                                        $sub = Category::where(['id' => $meta['id'],'parent' => $meta['sub_sub_sub_category_id']])->update([
                                            'slug' => $b
                                        ]);
                                    }
                                }
                                if($meta['type'] == 'SubSubSubSubSubCategory'){
                                    if($meta['id']  > 0){
                                        $sub = Category::where(['id' => $meta['id'],'parent' => $meta['sub_sub_sub_sub_category_id']])->update([
                                            'slug' => $b
                                        ]);
                                    }
                                }
                                if($meta['type'] == 'SubSubSubSubSubSubCategory'){
                                    if($meta['id']  > 0){
                                        $sub = Category::where(['id' => $meta['id'],'parent' => $meta['sub_sub_sub_sub_sub_category_id']])->update([
                                            'slug' => $b
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            return true;
        }catch(Exception $e){
            Log::info($e);
            dd($e->getMessage());
        }
    }

    public function rules(): array
    {
        return [
             // Can also use callback validation rules
             'unit_price' => function($attribute, $value, $onFailure) {
                  if (!is_numeric($value)) {
                       $onFailure('Unit price is not numeric');
                  }
              }
        ];
    }
}
