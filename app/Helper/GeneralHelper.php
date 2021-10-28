<?php

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Cache;

function getParentShowOf($parem)
{
    $route = str_replace('admin.', '', $parem);
    $permissinon = Cache::get('admin_side_menu')->where('as', $route)->first();
    return $permissinon ? $permissinon->parent_show : $route;
}


function getParentOf($parem)
{
    $route = str_replace('admin.', '', $parem);
    $permissinon =  Cache::get('admin_side_menu')->where('as', $route)->first();
    return $permissinon ? $permissinon->parent : $route;
}

function getParentIdOf($parem)
{
    $route = str_replace('admin.', '', $parem);
    $permissinon =  Cache::get('admin_side_menu')->where('as', $route)->first();
    return $permissinon ? $permissinon->id : null;
}

function getNumbers()
{
    $subtotal = Cart::instance('default')->subtotal();
    $discount = session()->has('coupon') ? session()->get('coupon')['discount'] : 0.00;
    $discount_code = session()->has('coupon') ? session()->get('coupon')['code'] : null;

    $subtotal_after_discount = $subtotal - $discount;

    $tax = config('cart.tax') / 100;
    $taxText = config('cart.tax') . '%';

    $productTaxes = round($subtotal_after_discount * $tax, 2);
    $newSubTotal = $subtotal_after_discount + $productTaxes;

    $shipping = session()->has('shipping') ? session()->get('shipping')['cost'] : 0.00;
    $shipping_code = session()->has('shipping') ? session()->get('shipping')['code'] : null;

    $total = ($newSubTotal + $shipping) > 0 ? round($newSubTotal + $shipping, 2) : 0.00;

    return collect([
        'subtotal' => $subtotal,
        'tax' => $productTaxes,
        'taxText' => $taxText,
        'productTaxes' => (float)$productTaxes,
        'newSubTotal' => (float)$newSubTotal,
        'discount' => (float)$discount,
        'discount_code' => $discount_code,
        'shipping' => (float)$shipping,
        'shipping_code' => $shipping_code,
        'total' => (float)$total,
    ]);
}