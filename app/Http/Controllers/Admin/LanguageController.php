<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Notifications\VendorCreated;
use Illuminate\Http\Request;
use App\Http\Requests\RequestLanguage;
use Validator;
class LanguageController extends Controller
{

    public function index()
    {
        $languages=Language::all();
        return view('admin.languages.index',compact('languages'));
    }


    public function create()
    {
        return view('admin.languages.create');
    }


    public function store(Request $request)
    {
        $validator=Validator::make($request->all(),[
            'name' => 'required|string|max:100',
            'abbr' => 'required|string|max:10',
            'active' => 'required',
            'direction' => 'required',
        ]);
        try {

           $language= Language::create($request->except(['_token']));
        
            return redirect()->route('admin.languages')->with(['success' => 'تم حفظ اللغة بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->route('admin.languages')->with(['error' => 'هناك خطا ما يرجي المحاوله فيما بعد']);
        }
    }



    public function edit($id)
    {
        $language = Language::find($id);
        return view('admin.languages.edit',compact('language'));
    }


    public function update($id,Request $request)
    {


        $language = Language::find($id);
            if (!$request->has('active'))
                $request->request->add(['active' => 0]);

            $language->update($request->except('_token'));

            return redirect()->route('admin.languages')->with(['success' => 'تم تحديث اللغة بنجاح']);


    }


    public function destroy($id)
    {

        $language = Language::find($id);
            $language->delete();

            return redirect()->route('admin.languages');


    }
}
