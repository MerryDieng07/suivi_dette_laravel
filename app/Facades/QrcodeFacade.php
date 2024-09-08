<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class QrcodeFacade extends Facade
{
    /**
     * Obtenir le nom du composant lié à la façade.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'QrcodeFacade';
    }
}