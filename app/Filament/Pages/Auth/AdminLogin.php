<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;

class AdminLogin extends BaseLogin
{
    protected static string $view = 'filament.pages.auth.admin-login';

    protected static string $layout = 'filament-panels::components.layout.base';
}