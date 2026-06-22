<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('sort_order', 'ASC')->orderBy('name', 'ASC')->get();
        return view('dashboard.categories.index', compact('categories'));
    }

    public function create()
    {
        $languages = auth()->user()->restaurant->getLanguages();
        return view('dashboard.categories.create', compact('languages'));
    }

    public function bulkCreate()
    {
        $languages = auth()->user()->restaurant->getLanguages();
        return view('dashboard.categories.bulk', compact('languages'));
    }

    public function bulkStore(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.name' => 'required|string|max:255',
            'categories.*.sort_order' => 'nullable|integer|min:0',
            'categories.*.status' => 'nullable|boolean',
        ]);

        $count = 0;
        foreach ($request->input('categories', []) as $catData) {
            if (empty(trim($catData['name'] ?? ''))) {
                continue;
            }

            Category::create([
                'restaurant_id' => auth()->user()->restaurant_id,
                'name'          => trim($catData['name']),
                'sort_order'    => isset($catData['sort_order']) && $catData['sort_order'] !== '' ? (int)$catData['sort_order'] : 0,
                'status'        => isset($catData['status']) ? (bool)$catData['status'] : true,
            ]);
            $count++;
        }

        return redirect()->route('dashboard.categories.index')
            ->with('success', "Successfully added {$count} categories.");
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create([
            ...$request->validated(),
            'restaurant_id' => auth()->user()->restaurant_id,
            'status'        => $request->boolean('status', true),
        ]);

        if ($request->hasFile('image')) {
            $category->addMediaFromRequest('image')->toMediaCollection('image');
        }

        // Save translations
        $this->saveTranslations($category, $request);

        return redirect()->route('dashboard.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        $category->load('translations');
        $languages = auth()->user()->restaurant->getLanguages();
        return view('dashboard.categories.edit', compact('category', 'languages'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update([
            ...$request->validated(),
            'status' => $request->boolean('status', true),
        ]);

        if ($request->hasFile('image')) {
            $category->clearMediaCollection('image');
            $category->addMediaFromRequest('image')->toMediaCollection('image');
        }

        // Save translations
        $this->saveTranslations($category, $request);

        return redirect()->route('dashboard.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->clearMediaCollection('image');
        $category->translations()->delete();
        $category->delete();

        return redirect()->route('dashboard.categories.index')
            ->with('success', 'Category deleted.');
    }

    /**
     * Save translations from the form's translations[locale][field] array.
     */
    private function saveTranslations(Category $category, $request): void
    {
        $translations = $request->input('translations', []);
        foreach ($translations as $locale => $fields) {
            if ($locale === 'en') continue; // English is stored in the main columns
            $category->setTranslations($locale, [
                'name' => $fields['name'] ?? '',
            ]);
        }
    }
}