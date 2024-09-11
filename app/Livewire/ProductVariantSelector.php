<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;

class ProductVariantSelector extends Component
{
    public $product;
    public $variants;
    public $selectedVariant;
    public $selectedVariantPrice;
    public $selectedVariantRegularPrice;
    public $hasDiscount = false;
    public $message = '';

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->variants = $product->variants;
        $this->selectedVariant = $this->variants->first()->id ?? null;
        $this->updateSelectedVariantPrice();
    }

    public function selectVariant($variantId)
    {
        $this->selectedVariant = $variantId;
        $this->updateSelectedVariantPrice();
        $this->resetMessage();
    }

    private function updateSelectedVariantPrice()
    {
        if ($this->selectedVariant) {
            $variant = $this->variants->find($this->selectedVariant);
            if ($variant) {
                $this->selectedVariantPrice = $variant->sale_price < $variant->regular_price ? $variant->sale_price : $variant->regular_price;
                $this->selectedVariantRegularPrice = $variant->regular_price;
                $this->hasDiscount = $variant->sale_price < $variant->regular_price;
            }
        } else {
            $this->selectedVariantPrice = $this->product->sale_price < $this->product->regular_price ? $this->product->sale_price : $this->product->regular_price;
            $this->selectedVariantRegularPrice = $this->product->regular_price;
            $this->hasDiscount = $this->product->sale_price < $this->product->regular_price;
        }
    }

    public function addToCart()
    {
        $cart = session()->get('cart', []);

        if ($this->variants->isNotEmpty()) {
            $variant = $this->variants->find($this->selectedVariant);

            if (!$variant || $variant->stock <= 0) {
                $this->message = 'This variant is out of stock.';
                return;
            }

            $variantKey = $this->product->id . '-' . $variant->id;

            if (isset($cart[$variantKey])) {
                if (($cart[$variantKey]['quantity'] + 1) > $variant->stock) {
                    $this->message = 'This variant is out of stock.';
                    return;
                }
                $cart[$variantKey]['quantity']++;
            } else {
                $cart[$variantKey] = [
                    'product_id' => $this->product->id,
                    'variant_id' => $variant->id,
                    'name' => $this->product->name,
                    'image' => $this->product->image,
                    'price' => $this->selectedVariantPrice,
                    'regular_price' => $this->selectedVariantRegularPrice,
                    'stock' => $variant->stock,
                    'variant_attributes' => $variant->variantAttributes->pluck('attributeValue.value', 'attribute.name')->toArray(),
                    'quantity' => 1,
                ];
            }
        } else {
            if ($this->product->stock <= 0) {
                $this->message = 'This product is out of stock.';
                return;
            }

            $productKey = $this->product->id;

            if (isset($cart[$productKey])) {
                if (($cart[$productKey]['quantity'] + 1) > $this->product->stock) {
                    $this->message = 'This product is out of stock.';
                    return;
                }
                $cart[$productKey]['quantity']++;
            } else {
                $cart[$productKey] = [
                    'product_id' => $this->product->id,
                    'name' => $this->product->name,
                    'image' => $this->product->image,
                    'price' => $this->selectedVariantPrice,
                    'regular_price' => $this->selectedVariantRegularPrice,
                    'stock' => $this->product->stock,
                    'quantity' => 1,
                ];
            }
        }

        session()->put('cart', $cart);
        $this->dispatch('productAddedToCart');
        $this->message = 'Added to cart';
    }

    public function resetMessage()
    {
        $this->message = '';
    }

    public function render()
    {
        return view('livewire.product-variant-selector');
    }
}
