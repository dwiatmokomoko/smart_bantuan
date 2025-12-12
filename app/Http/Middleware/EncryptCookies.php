<?php
namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Pastikan XSRF-TOKEN tidak terenkripsi
        'XSRF-TOKEN',
        // jika kamu memakai laravel_session di cookie custom, biasanya jangan masukkan laravel_session
    ];
}
