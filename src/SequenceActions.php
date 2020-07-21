<?php

namespace Marshmallow\Nova\Actions\Sequence;

use Marshmallow\Nova\Actions\Sequence\SequenceLast;
use Marshmallow\Nova\Actions\Sequence\SequenceFirst;
use Marshmallow\Nova\Actions\Sequence\SequencePlace;

class SequenceActions
{
	public static function make($direction = 'asc', $column = 'order')
	{
		return [
    		new SequenceFirst($direction, $column),
    		new SequenceLast($direction, $column),
    		new SequencePlace($direction, $column),
    	];
	}
}
