<?php

namespace App\Services\Facades;

use Illuminate\Support\Facades\Facade;

class DocumentFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'DocumentService';
    }
}