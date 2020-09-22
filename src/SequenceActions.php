<?php

namespace Marshmallow\Nova\Actions\Sequence;

use Marshmallow\Nova\Actions\Sequence\SequenceLast;
use Marshmallow\Nova\Actions\Sequence\SequenceFirst;
use Marshmallow\Nova\Actions\Sequence\SequencePlace;

class SequenceActions
{
	protected $groupBy;

	public function groupBy($groupBy)
	{
		$this->groupBy = $groupBy;
		return $this;
	}

	public function make($direction = 'asc', $column = 'sequence')
	{
		return [
    		new SequenceFirst($direction, $column, $this->groupBy),
    		new SequenceLast($direction, $column, $this->groupBy),
    		new SequencePlace($direction, $column, $this->groupBy),
    	];
	}
}
