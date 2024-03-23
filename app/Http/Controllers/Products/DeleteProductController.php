<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Response;

class DeleteProductController extends Controller
{
    public function __invoke(Product $product): Response
    {
        $product->delete();

        return response()->noContent();
    }
}
