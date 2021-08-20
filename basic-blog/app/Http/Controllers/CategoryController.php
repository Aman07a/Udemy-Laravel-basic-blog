<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CategoryController extends Controller
{
    public function AllCat()
    {
        // $categories = DB::table('categories')
        //     ->join('users', 'categories.user_id', 'user_id')
        //     ->select('categories.*', 'users.name')
        //     ->latest()->paginate(5);

        $categories = Category::latest()->paginate(5);
        // $categories = DB::table('categories')->latest()->paginate(5);
        return view('admin.category.index', compact('categories'));
    }

    public function AddCat(Request $request)
    {
        $validatedData = $request->validate(
            [
                'category_name' => 'required|unique:categories|max:255'
            ],
            [
                'category_name.required' => 'Please Input Category Name',
                'category_name.max' => 'Category Less Then 255 Characters'
            ]
        );

        // Category::insert([
        //     'category_name' => $request->category_name,
        //     'user_id' => Auth::user()->id,
        //     'created_at' => Carbon::now(),
        // ]);

        // $category = new Category;
        // $category->category_name = $request->category_name;
        // $category->user_id = Auth::user()->id;
        // $category->save();

        $data = array();
        $data['category_name'] = $request->category_name;
        $data['user_id'] = Auth::user()->id;
        $data['created_at'] = Carbon::now();
        DB::table('categories')->insert($data);

        // Redirect
        return Redirect()->back()->with('success', 'Category Inserted Successfull');
    }

    public function Edit($id)
    {
        $categories = Category::find($id);
        return view('admin.category.edit', compact('categories'));
    }

    public function Update(Request $request, $id)
    {
        $update = Category::find($id)->update([
            'category_name' => $request->category_name,
            'user_id' => Auth::user()->id,
            'updated_at' => Carbon::now(),
        ]);

        // Redirect
        return Redirect()->route('all.category')->with('success', 'Category Updated Successfull');
    }

    public function Delete()
    {
    }
}
