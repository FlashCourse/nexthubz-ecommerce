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
    public $message = '';

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->variants = $product->variants;
        $this->selectedVariant = $this->variants->first()->id ?? null;
        $this->selectedVariantPrice = $this->variants->first()->price ?? $product->price;
    }

    public function selectVariant($variantId)
    {
        $this->selectedVariant = $variantId;
        $variant = $this->variants->find($variantId);
        $this->selectedVariantPrice = $variant ? $variant->price : $this->product->price;
        $this->resetMessage();
    }

    public function addToCart()
    {
        if ($this->variants->isNotEmpty()) {
            $variant = $this->variants->find($this->selectedVariant);

            if (!$variant || $variant->stock <= 0) {
                $this->message = 'This variant is out of stock.';
                return;
            }

            $cart = session()->get('cart', []);
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
                    'price' => $variant->price,
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

            $cart = session()->get('cart', []);
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
                    'price' => $this->product->price,
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
