<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;

class ProductFilters extends Component
{
    public $minPrice = '';
    public $maxPrice = '';
    public $categories;
    public $selectedCategories = [];
    public $selectedRating = '';
    public $queryParams;

    public function mount()
    {
        $this->queryParams = request()->query();
        $this->categories = Category::take(8)->get();

        $this->minPrice = $this->queryParams['minPrice'] ?? '';
        $this->maxPrice = $this->queryParams['maxPrice'] ?? '';
        $this->selectedRating = $this->queryParams['rating'] ?? '';

        if (isset($this->queryParams['categories'])) {
            $selectedCategoriesFromUrl = $this->queryParams['categories'];
            $this->selectedCategories = is_array($selectedCategoriesFromUrl) ? $selectedCategoriesFromUrl : explode(',', $selectedCategoriesFromUrl);
        }
    }

    public function priceFilter()
    {
        $this->generateQueryParams();
        $this->redirect(route('product.search', $this->queryParams));
    }

    public function updateSelectedCategories($value)
    {
        if (in_array($value, $this->selectedCategories)) {
            $this->selectedCategories = array_diff($this->selectedCategories, [$value]);
        } else {
            $this->selectedCategories[] = $value;
        }

        $this->generateQueryParams();
        $this->redirect(route('product.search', $this->queryParams));
    }

    public function updateRating($value)
    {
        $this->selectedRating = $value;
        $this->generateQueryParams();
        $this->redirect(route('product.search', $this->queryParams));
    }

    public function resetFilters()
    {
        $this->minPrice = '';
        $this->maxPrice = '';
        $this->selectedCategories = [];
        $this->selectedRating = '';
        $this->generateQueryParams();
        $this->redirect(route('product.search'));
    }

    private function generateQueryParams()
    {
        $this->queryParams = []; // Reset the queryParams

        if (!empty($this->minPrice) && is_numeric($this->minPrice)) {
            $this->queryParams['minPrice'] = $this->minPrice;
        }

        if (!empty($this->maxPrice) && is_numeric($this->maxPrice)) {
            $this->queryParams['maxPrice'] = $this->maxPrice;
        }

        if (!empty($this->selectedCategories)) {
            $this->queryParams['categories'] = implode(',', $this->selectedCategories);
        }

        if (!empty($this->selectedRating)) {
            $this->queryParams['rating'] = $this->selectedRating;
        }
    }

    public function render()
    {
        return view('livewire.product-filters');
    }
}
