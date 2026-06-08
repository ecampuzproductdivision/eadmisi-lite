<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Menu;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::with('menu')->orderBy('sort_order')->paginate(10);
        return view('settings.pages.index', compact('pages'));
    }

    public function create()
    {
        $menus = Menu::all();
        return view('settings.pages.create', compact('menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'page_name' => 'required|string|max:255',
            'page_code' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'menu_id' => 'nullable|exists:menus,id',
            'icon' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        Page::create($request->all());
        return redirect()->route('pages.index')->with('success', 'Page created successfully.');
    }

    public function edit(Page $page)
    {
        $menus = Menu::all();
        return view('settings.pages.edit', compact('page', 'menus'));
    }

    public function update(Request $request, Page $page)
    {
        $request->validate([
            'page_name' => 'required|string|max:255',
            'page_code' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'menu_id' => 'nullable|exists:menus,id',
            'icon' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $page->update($request->all());
        return redirect()->route('pages.index')->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return redirect()->route('pages.index')->with('success', 'Page deleted successfully.');
    }
}