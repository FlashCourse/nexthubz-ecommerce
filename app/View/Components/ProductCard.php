<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductCard extends Component
{
    public $product;
    public $discount;

    /**
     * Create a new component instance.
     *
     * @param  mixed  $product
     * @param  mixed  $discount
     * @return void
     */
    public function __construct($product, $discount = null)
    {
        $this->product = $product;
        $this->discount = $discount;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render(): View|Closure|string
    {
        return view('components.product-card');
    }
}
