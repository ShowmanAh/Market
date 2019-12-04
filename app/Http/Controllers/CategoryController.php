<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //search categories
        $categories = Category::when($request->search,function($q) use ($request){
            return $q->where('name','like','%' . $request->search . '%');
        })->latest()->paginate(5);

        return view('dashboard.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //name ar or required and unique
        $rules = [];
        foreach (config('translatable.locales') as $locale){
            $rules += [$locale . '.name' => ['required', Rule::unique('category_translations','name')]];
        }

       $request->validate($rules);
       //dd($request);
      Category::create($request->all());
        session()->flash('success', __('site.added_successfully'));
        //return view('dashboard.users.index');

        return redirect()->route('dashboard.categories.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('dashboard.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //name ar or required and unique
        $rules = [];
        foreach (config('translatable.locales') as $locale){
            $rules += [$locale . '.name' => ['required', Rule::unique('category_translations','name')->ignore($category->id, 'category_id')]];
        }
        $request->validate($rules);
        $category->update($request->all());
        session()->flash('success', __('site.edit_successfully'));
        //return view('dashboard.users.index');

        return redirect()->route('dashboard.categories.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
        session()->flash('success', __('site.deleted_successfully'));
        //return view('dashboard.users.index');

        return redirect()->route('dashboard.categories.index');
    }
}
