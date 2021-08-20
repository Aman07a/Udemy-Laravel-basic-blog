<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class BrandController extends Controller
{
    public function AllBrand()
    {
        $brands = DB::table('brands')
            ->latest()->paginate(5);
        return view('admin.brand.index', compact('brands'));
    }

    public function StoreBrand(Request $request)
    {
        $validatedData = $request->validate(
            [
                'brand_name' => 'required|unique:brands|min:4',
                'brand_image' => 'required|mimes:jpg,jpeg,png',
            ],
            [
                'brand_name.required' => 'Please Input Brand Name',
                'brand_image.min' => 'Brand Longer Then 4 Characters',
            ]
        );

        $brand_image = $request->file('brand_image');

        $name_gen = hexdec(uniqid());
        $img_text = strtolower($brand_image->getClientOriginalExtension());
        $img_name = $name_gen . '.' . $img_text;
        $up_location = 'image/brand/';
        $last_img = $up_location . $img_name;
        $brand_image->move($up_location, $img_name);

        Brand::insert([
            'brand_name' => $request->brand_name,
            'brand_image' => $last_img,
            'created_at' => Carbon::now(),
        ]);

        // Redirect
        return Redirect()->back()->with('success', 'Brand Inserted Successfully');
    }

    public function Edit($id)
    {
        // $brands = Brand::find($id);
        $brands = DB::table('brands')->where('id', $id)->first();
        return view('admin.brand.edit', compact('brands'));
    }

    public function Update(Request $request, $id)
    {

        $validatedData = $request->validate(
            [
                'brand_name' => 'required|min:4',
            ],
            [
                'brand_name.required' => 'Please Input Brand Name',
                'brand_image.min' => 'Brand Longer Then 4 Characters',
            ]
        );

        $old_image = $request->old_image;

        $brand_image = $request->file('brand_image');

        if ($brand_image) {
            $name_gen = hexdec(uniqid());
            $img_text = strtolower($brand_image->getClientOriginalExtension());
            $img_name = $name_gen . '.' . $img_text;
            $up_location = 'image/brand/';
            $last_img = $up_location . $img_name;
            $brand_image->move($up_location, $img_name);

            unlink($old_image);

            Brand::find($id)->update([
                'brand_name' => $request->brand_name,
                'brand_image' => $last_img,
                'updated_at' => Carbon::now(),
            ]);

            // Redirect
            return Redirect()->route('all.brand')->with('success', 'Brand Updated Successfully');
        } else {
            Brand::find($id)->update([
                'brand_name' => $request->brand_name,
                'updated_at' => Carbon::now(),
            ]);

            // Redirect
            return Redirect()->route('all.brand')->with('success', 'Brand Updated Successfully');
        }
    }

    // public function SoftDelete($id)
    // {
    //     $delete = Brand::find($id)->delete();

    //     // Redirect
    //     return Redirect()->back()->with('success', 'Brand Soft Delete Successfully');
    // }

    // public function Restore($id)
    // {
    //     $restore = Brand::withTrashed()->find($id)->restore();

    //     // Redirect
    //     return Redirect()->back()->with('success', 'Brand Restored Successfully');
    // }

    // public function Pdelete($id)
    // {
    //     $pdelete = Brand::onlyTrashed()->find($id)->forceDelete();

    //     // Redirect
    //     return Redirect()->back()->with('success', 'Brand Permanently Deleted');
    // }
}
