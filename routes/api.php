<?php

/**
 * Retornando a api produtos
 */
Route::resource('/products', 'ProductsController')->only([
    'index', 'update', 'store', 'destroy'
]);

