<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function AllCat() {
        $categories = DB::table('categories')->get();
        return view('admin.category.index', compact('categories'));
    }
}
