<?php

namespace App\Traits;

use SystemHelper;

trait HasLocaleAttributes
{
    public function getTitleAttribute(): string
    {
        return $this->{SystemHelper::getFieldLocale('title')};
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->{SystemHelper::getFieldLocale('description')};
    }
}
