<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Vendor;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use App;
use App\Http\Requests\RequestMainCategory;
use Validator;
class MainCategoryController extends Controller
{

    public function index()
    {


         $defult_lang=App::getLocale();
         $mainCategories=MainCategory::where('translation_lang',$defult_lang)->selection()->get();
         return view('admin.mainCategories.index',compact('mainCategories','defult_lang'));

    }


    public function create()
    {

        $getLangouge= \App\Models\Language::active()->selection()->get();
        return view('admin.mainCategories.create',compact('getLangouge'));
    }


    public function store(RequestMainCategory $request)
    {

         try{
            $main_categories = collect($request->category);

            $filter = $main_categories->filter(function ($value, $key) {
                return $value['abbr'] ==App::getLocale();
            });

            $default_category = array_values($filter->all()) [0];
            if (!$request->has('category.0.active'))
            $request->request->add(['active' => 0]);
            else
            $request->request->add(['active' => 1]);





             $filePath = "";
            if ($request->has('photo')) {

                $request->photo->store('/', 'maincategories');
                $filename = $request->photo->hashName();
                $filePath = 'images/' . 'maincategories' . '/' . $filename;
            }


          $default_category_id = MainCategory::insertGetId([
                'translation_lang' => $default_category['abbr'],
                'translation_of' => 0,
                'name' => $default_category['name'],
                'slug' => $default_category['name'],
                'active' => $request->active,
                'photo' =>$filePath

            ]);

            $categories = $main_categories->filter(function ($value, $key) {
                return $value['abbr'] != App::getLocale();
            });
            if (isset($categories) && $categories->count()) {

                $categories_arr = [];
                foreach ($categories as $category) {
                    $categories_arr[] = [
                        'translation_lang' => $category['abbr'],
                        'translation_of' => $default_category_id,
                        'name' => $category['name'],
                        'slug' => $category['name'],
                        'active' => $request->active,
                        'photo' =>$filePath
                    ];
                     }
                   }
                MainCategory::insert($categories_arr);
                return redirect()->route('admin.maincategories')->with(['success' => 'تم الحفظ بنجاح']);
                    }
                    catch (\Exception $ex)
                    {
                        return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
                    }

    }



     public function edit( $mainCat_id)
    {
        $mainCategory = MainCategory::with('categories')
        ->selection()
        ->find($mainCat_id);

        if (!$mainCategory)
            return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود ']);

        return view('admin.maincategories.edit', compact('mainCategory'));
    }


    public function update(RequestMainCategory $request, $mainCat_id)
    {
        try {
            $main_category = MainCategory::find($mainCat_id);

            if (!$main_category)
                return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود ']);

            // update date

            $category = array_values($request->category) [0];

            if (!$request->has('category.0.active'))
                $request->request->add(['active' => 0]);
            else
                $request->request->add(['active' => 1]);


            MainCategory::where('id', $mainCat_id)
                ->update([
                    'name' => $category['name'],
                    'active' => $request->active,
                ]);

            // save image
            $filePath = "";
            if ($request->has('photo')) {

                $request->photo->store('/', 'maincategories');
                $filename = $request->photo->hashName();
                $filePath = 'images/' . 'maincategories' . '/' . $filename;
                MainCategory::where('id', $mainCat_id)
                    ->update([
                        'photo' => $filePath,
                    ]);
            }




            return redirect()->route('admin.maincategories')->with(['success' => 'تم ألتحديث بنجاح']);
        } catch (\Exception $ex) {

            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }


    public function destroy($id)
    {
        try{
            $mainCategory=  MainCategory::find($id);

            if (!$mainCategory)
            return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود ']);

            $vendors=$mainCategory->vendors();

            if(isset($vendors) && $vendors->count() > 0)
            {
              return redirect()->route('admin.maincategories')->with(['error' => 'لأ يمكن حذف هذا القسم  ']);
            }
          /*  // delete photo// */
             $image = Str::after($mainCategory->photo, 'assets/');
            $image = base_path('public/assets/' . $image);
             unlink($image);

             $mainCategory->categories()->delete();
             $mainCategory->delete();

            return redirect()->route('admin.maincategories')->with(['success' => 'تم حذف القسم بنجاح']);
             }

                 catch (\Exception $ex) {
                     return $ex;
             return redirect()->route('admin.maincategories')->with(['error' => 'هناك خطا ما يرجي المحاوله فيما بعد']);
                 }
    }

    public function changeStatus($id)
    {
        try{
            $mainCategory=  MainCategory::find($id);

            if (!$mainCategory)
            return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود ']);
            $status =  $mainCategory -> active  == 0 ? 1 : 0;

            $mainCategory -> update(['active' =>$status ]);
            return redirect()->route('admin.maincategories')->with(['success' => ' تم تغيير الحالة بنجاح ']);

        }
        catch (\Exception $ex) {
            return $ex;
            return redirect()->route('admin.maincategories')->with(['error' => 'هناك خطا ما يرجي المحاوله فيما بعد']);
        }
    }
}
