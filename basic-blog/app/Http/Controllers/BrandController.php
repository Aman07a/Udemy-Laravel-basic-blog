<?php

namespace App\Http\Controllers;


use App\Models\Brand;
use App\Models\Multipic;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Redirect;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
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

        // $name_gen = hexdec(uniqid());
        // $img_text = strtolower($brand_image->getClientOriginalExtension());
        // $img_name = $name_gen . '.' . $img_text;
        // $up_location = 'image/brand/';
        // $last_img = $up_location . $img_name;
        // $brand_image->move($up_location, $img_name);

        $name_gen = hexdec(uniqid()) . '.' . $brand_image->getClientOriginalExtension();
        Image::make($brand_image)->resize(300, 200)->save('image/brand/' . $name_gen);

        $last_img = 'image/brand/' . $name_gen;

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

    public function Delete($id)
    {
        $image = Brand::find($id);
        $old_image = $image->brand_image;
        unlink($old_image);

        Brand::find($id)->delete();

        // Redirect
        return Redirect()->back()->with('success', 'Brand Delete Successfully');
    }

    // This is for Multi Image All Methods
    public function Multipic()
    {
        // $images = Multipic::all();
        $images = DB::table('multipics')->latest()->paginate(5);
        return view('admin.multipic.index', compact('images'));
    }

    public function StoreImg(Request $request)
    {
        $image = $request->file('image');

        foreach ($image as $multi_img) {

            $name_gen = hexdec(uniqid()) . '.' . $multi_img->getClientOriginalExtension();
            Image::make($multi_img)->resize(300, 200)->save('image/multi/' . $name_gen);

            $last_img = 'image/multi/' . $name_gen;

            Multipic::insert([
                'image' => $last_img,
                'created_at' => Carbon::now(),
            ]);
        } // End of the feoreach

        // Redirect
        return Redirect()->back()->with('success', 'Brand Inserted Successfully');
    }
}
