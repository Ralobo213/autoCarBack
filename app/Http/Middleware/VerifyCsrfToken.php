<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
         'register',// Ajouter cette ligne pour exclure la vérification CSRF pour la route de registration
        'login', //: Ajouter cette ligne pour exclure la vérification CSRF pour la route de login
        // Ajoutez d'autres routes que vous voulez exclure
    ];
}
