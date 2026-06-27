<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Menu;
use App\Helpers\SortHelper;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $query = Page::with('menu');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('page_name', 'like', "%{$s}%")
                  ->orWhere('page_code', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $pages = SortHelper::apply($query, [
            'page_name', 'page_code', 'menu_id', 'url', 'route_path', 'component_name', 'sort_order', 'is_active', 'created_at'
        ], 'sort_order', 'asc')->get();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('settings.pages.partials.page_rows', compact('pages'))->render(),
            ]);
        }

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