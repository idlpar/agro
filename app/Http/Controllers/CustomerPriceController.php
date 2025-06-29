<?php

namespace App\Http\Controllers;

use App\Models\CustomerPrice;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerPriceController extends Controller
{
    public function index(User $customer)
    {
        $prices = $customer->customerPrices()->with('variant.product')->get();
        return view('customers.prices.index', compact('customer', 'prices'));
    }

    public function create(User $customer)
    {
        $variants = ProductVariant::with('product')
            ->whereDoesntHave('customerPrices', function ($query) use ($customer) {
                $query->where('user_id', $customer->id);
            })
            ->get();

        return view('customers.prices.create', compact('customer', 'variants'));
    }

    public function store(Request $request, User $customer)
    {
        $validated = $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'price' => 'required|numeric|min:0',
        ]);

        $customer->customerPrices()->create($validated);

        return redirect()->route('customers.prices.index', $customer)->with('success', 'Price set successfully');
    }

    public function edit(CustomerPrice $price)
    {
        return view('customers.prices.edit', compact('price'));
    }

    public function update(Request $request, CustomerPrice $price)
    {
        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
        ]);

        $price->update($validated);

        return redirect()->route('customers.prices.index', $price->customer)->with('success', 'Price updated successfully');
    }

    public function destroy(CustomerPrice $price)
    {
        $price->delete();
        return back()->with('success', 'Price removed successfully');
    }
}
