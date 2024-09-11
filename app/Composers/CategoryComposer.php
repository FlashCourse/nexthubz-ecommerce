<?php

namespace App\Composers;

use Illuminate\View\View;
use App\Models\Category;

class CategoryComposer
{
    public function compose(View $view): void
    {
        // Fetch only categories with no parent (parent_id is null)
        $categories = Category::whereNull('parent_id')->take(10)->get();

        // Bind the categories to the view
        $view->with('composerCategories', $categories);
    }
}
