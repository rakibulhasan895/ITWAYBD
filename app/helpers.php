<?php
if (! function_exists('calculateSaleTotal')) {
function calculateSaleTotal($items) {
return collect($items)->sum(fn($item) => ($item['quantity'] * $item['price']) - $item['discount']);
}
}