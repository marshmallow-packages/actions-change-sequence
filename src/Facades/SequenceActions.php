<?php

namespace Marshmallow\Nova\Actions\Sequence\Facades;

use Illuminate\Support\Facades\Facade;
use Marshmallow\Nova\Actions\Sequence\SequenceActions as BaseSequenceActions;

class SequenceActions extends Facade
{
    protected static function getFacadeAccessor()
    {
        return BaseSequenceActions::class;
    }
}
