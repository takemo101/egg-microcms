<?php

use Module\Database\DatabaseModule;
use Module\View\ViewModule;
use Takemo101\Egg\Module\HelperModule;
use Takemo101\Egg\Module\Modules;

return function (Modules $modules) {
    $modules->add(
        HelperModule::class,
        ViewModule::class,
        DatabaseModule::class,
    );
};
