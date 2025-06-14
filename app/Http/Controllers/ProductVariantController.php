<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function create(Product $product)
    {
        $this->authorize('create', ProductVariant::class);
        return view('products.variants.create', compact('product'));
    }

    public function store(Request $request, Product $product)
    {
        $this->authorize('create', ProductVariant::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'default_price' => 'required|numeric|min:0',
        ]);

        $product->variants()->create($validated);

        return redirect()->route('products.show', $product)
            ->with('success', 'Variant added successfully');
    }

    public function edit(Product $product, ProductVariant $variant)
    {
        $this->authorize('update', $variant);

        // Optional: You can check if the variant belongs to the product
        abort_if($variant->product_id !== $product->id, 404);

        return view('products.variants.edit', compact('variant', 'product'));
    }

    public function update(Request $request, Product $product, ProductVariant $variant)
    {
        $this->authorize('update', $variant);

        // Ensure the variant belongs to the given product
        abort_if($variant->product_id !== $product->id, 404);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'default_price' => 'required|numeric|min:0',
        ]);

        $variant->update($validated);

        return redirect()->route('products.show', $product)
            ->with('success', 'Variant updated successfully');
    }


    public function destroy(Product $product, ProductVariant $variant)
    {
        $this->authorize('delete', $variant);

        // Ensure the variant belongs to the given product
        abort_if($variant->product_id !== $product->id, 404);

        $variant->delete();

        return redirect()->route('products.show', $product)
            ->with('success', 'Variant deleted successfully');
    }

}
