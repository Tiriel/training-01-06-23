<?php

namespace App\Notifier\Factory;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.notification_factory')]
interface IterableFactoryInterface
{
    public static function getDefaultIndex(): string;
}
