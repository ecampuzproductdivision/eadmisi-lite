<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with(['children' => function ($q) {
            $q->orderBy('sort_order');
        }])
        ->whereNull('parent_id')
        ->withCount('children')
        ->orderBy('sort_order')
        ->paginate(10);
        return view('settings.menus.index', compact('menus'));
    }

    public function create()
    {
        $parentMenus = Menu::whereNull('parent_id')->get();
        return view('settings.menus.create', compact('parentMenus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'menu_name' => 'required|string|max:255',
            'menu_code' => 'required|string|max:255|unique:menus',
            'category' => 'nullable|in:MASTER_DATA,TRANSACTION,SETTINGS',
            'parent_id' => 'nullable|exists:menus,id',
            'icon' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        Menu::create($request->all());
        return redirect()->route('menus.index')->with('success', 'Menu created successfully.');
    }

    public function edit(Menu $menu)
    {
        $parentMenus = Menu::whereNull('parent_id')->where('id', '!=', $menu->id)->get();
        return view('settings.menus.edit', compact('menu', 'parentMenus'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'menu_name' => 'required|string|max:255',
            'menu_code' => 'required|string|max:255|unique:menus,menu_code,' . $menu->id,
            'category' => 'nullable|in:MASTER_DATA,TRANSACTION,SETTINGS',
            'parent_id' => 'nullable|exists:menus,id',
            'icon' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $menu->update($request->all());
        return redirect()->route('menus.index')->with('success', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('menus.index')->with('success', 'Menu deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $request->validate(['order' => 'required|array']);
        foreach ($request->order as $item) {
            Menu::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }
        return response()->json(['success' => true]);
    }
}