<?php

namespace App\Http\Controllers\Admin;
use App\Http\Requests\RequestVendor;
use DB;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\MainCategory;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::selection()->paginate(10);
        return view('admin.vendors.index', compact('vendors'));
    }
    public function create()
    {
        $categories = MainCategory::where('translation_of', 0)->active()->get();
        return view('admin.vendors.create', compact('categories'));
    }


    public function store(RequestVendor $request)
    {

        try {

            if (!$request->has('active'))
                $request->request->add(['active' => 0]);
            else
                $request->request->add(['active' => 1]);

                $filePath = "";
                if ($request->has('logo')) {

                    $request->logo->store('/', 'vendors');
                    $filename = $request->logo->hashName();
                    $filePath = 'images/' . 'vendors' . '/' . $filename;
                }

            $vendor = Vendor::create([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'active' => $request->active,
                'address' => $request->address,
                'password' => $request->password,
                'logo' => $filePath,
                'category_id' => $request->category_id,

            ]);


            return redirect()->route('admin.vendors')->with(['success' => 'تم الحفظ بنجاح']);

        } catch (\Exception $ex) {
            return $ex;
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);

        }
    }
    public function edit($id)
    {
        try {

            $vendor = Vendor::Selection()->find($id);
            if (!$vendor)
                return redirect()->route('admin.vendors')->with(['error' => 'هذا المتجر غير موجود او ربما يكون محذوفا ']);

            $categories = MainCategory::where('translation_of', 0)->active()->get();

            return view('admin.vendors.edit', compact('vendor', 'categories'));

        } catch (\Exception $exception) {
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }
    public function update($id, RequestVendor $request)
    {

        try {

            $vendor = Vendor::Selection()->find($id);
            if (!$vendor)
                return redirect()->route('admin.vendors')->with(['error' => 'هذا المتجر غير موجود او ربما يكون محذوفا ']);


            DB::beginTransaction();
            //photo
            if ($request->has('logo') ) {
                $filePath = "";
                if ($request->has('logo')) {

                    $request->logo->store('/', 'vendors');
                    $filename = $request->logo->hashName();
                    $filePath = 'images/' . 'vendors' . '/' . $filename;
                }
                Vendor::where('id', $id)
                    ->update([
                        'logo' => $filePath,
                    ]);
            }


            if (!$request->has('active'))
                $request->request->add(['active' => 0]);
            else
                $request->request->add(['active' => 1]);

             $data = $request->except('_token', 'id', 'logo', 'password');


            if ($request->has('password') && !is_null($request->  password)) {

                $data['password'] = $request->password;
            }

            Vendor::where('id', $id)
                ->update(
                    $data
                );

            DB::commit();
            return redirect()->route('admin.vendors')->with(['success' => 'تم التحديث بنجاح']);
        } catch (\Exception $exception) {
            return $exception;
            DB::rollback();
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }

    }
    public function destroy($id)
    {
        try {

            $vendor = Vendor::Selection()->find($id);
            if (!$vendor)
                return redirect()->route('admin.vendors')->with(['error' => 'هذا المتجر غير موجود  ']);
           $image = Str::after($vendor->logo, 'assets/');
           $image = base_path('public/assets/' . $image);
           unlink($image);
           $vendor->delete();
                return redirect()->route('admin.vendors')->with(['success' => 'تم حذف القسم بنجاح']);
            }
           catch(\Exception $exception )
            {
                return redirect()->route('admin.vendors')->with(['error' => 'هذا المتجر غير موجود او ربما يكون محذوفا ']);
              }
}
public function changeStatus($id)
    {
        try{
            $vendor = Vendor::Selection()->find($id);

            if (!$vendor)
            return redirect()->route('admin.vendors')->with(['error' => 'هذا المتجر غير موجود  ']);

            $status =  $vendor -> active  == 0 ? 1 : 0;

            $vendor -> update(['active' =>$status ]);
            return redirect()->route('admin.vendors')->with(['success' => ' تم تغيير الحالة بنجاح ']);

        }
        catch (\Exception $ex) {

            return redirect()->route('admin.vendors')->with(['error' => 'هناك خطا ما يرجي المحاوله فيما بعد']);
        }
    }
}
