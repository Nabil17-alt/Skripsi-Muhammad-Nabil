<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('images')->orderByDesc('created_at')->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:tb_products,slug|max:255',
            'base_price' => 'required|numeric|min:0',
            'design_service_fee' => 'nullable|numeric|min:0',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'lead_time_days' => 'nullable|integer|min:1',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        /** @var Product $product */
        $product = Product::create([
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
            'category' => $request->input('category'),
            'description' => $request->input('description'),
            'base_price' => $request->input('base_price'),
            'lead_time_days' => $request->input('lead_time_days', 1),
            'allow_custom_design' => $request->has('allow_custom_design'),
            'allow_design_service' => $request->has('allow_design_service'),
            'design_service_fee' => $request->input('design_service_fee', 0),
            'is_active' => $request->has('is_active'),
        ]);

        // Handle image upload
        if ($request->hasFile('images')) {
            $isPrimary = true;
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $isPrimary,
                ]);
                $isPrimary = false;
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        /** @var Product $product */
        $product = $product->load('images', 'variants');
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        /** @var Product $product */
        $product = $product->load('images');
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        /** @var Product $product */
        $request->validate([
            'name' => 'nullable|string|max:255',
            'slug' => 'nullable|string|unique:tb_products,slug,' . $product->id . '|max:255',
            'base_price' => 'nullable|numeric|min:0',
            'design_service_fee' => 'nullable|numeric|min:0',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'lead_time_days' => 'nullable|integer|min:1',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'primary_image_id' => 'nullable|integer|exists:tb_product_images,id',
        ]);

        $product->update([
            'name' => $request->filled('name') ? $request->input('name') : $product->name,
            'slug' => $request->filled('slug') ? $request->input('slug') : $product->slug,
            'category' => $request->input('category'),
            'description' => $request->input('description'),
            'base_price' => $request->filled('base_price') ? $request->input('base_price') : $product->base_price,
            'lead_time_days' => $request->filled('lead_time_days') ? $request->input('lead_time_days') : $product->lead_time_days,
            'allow_custom_design' => $request->has('allow_custom_design'),
            'allow_design_service' => $request->has('allow_design_service'),
            'design_service_fee' => $request->filled('design_service_fee') ? $request->input('design_service_fee') : $product->design_service_fee,
            'is_active' => $request->has('is_active'),
        ]);

        // Handle new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => false,
                ]);
            }
        }

        // Set primary image
        if ($request->input('primary_image_id')) {
            ProductImage::where('product_id', $product->id)->update(['is_primary' => false]);
            ProductImage::where('product_id', $product->id)
                ->where('id', $request->input('primary_image_id'))
                ->update(['is_primary' => true]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        /** @var Product $product */
        /** @var ProductImage $image */
        // Delete all related images
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path ?? '');
            $image->delete();
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function deleteImage(ProductImage $image)
    {
        /** @var ProductImage $image */
        $product = $image->product;
        Storage::disk('public')->delete($image->image_path ?? '');
        $image->delete();

        return back()->with('success', 'Gambar berhasil dihapus.');
    }
}
