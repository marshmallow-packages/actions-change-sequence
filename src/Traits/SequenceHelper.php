<?php

namespace Marshmallow\Nova\Actions\Sequence\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

trait SequenceHelper
{
	protected $column;

	protected $direction;

	public function __construct($direction = 'asc', $column = 'order')
    {
    	$this->direction = strtolower($direction);
    	$this->column = $column;
    }

    protected function oppositeDirection()
    {
    	return ($this->direction == 'asc') ? 'desc' : 'asc';
    }

	protected function getResourceClass(Model $model)
    {
    	return get_class($model);
    }

    protected function getAllModelsOrdered(Collection $models)
    {
    	$resource_class = $this->getResourceClass($models->first());
    	return $resource_class::orderBy($this->column, $this->direction)->get();
    }

    protected function changeSequenceWithLargeNumber(Model $model, $add = 1000)
    {
    	$resource_class = $this->getResourceClass($model);
    	$models = $resource_class::orderBy($this->column, $this->direction)->get();
    	foreach ($models as $model) {
    		$this->setSequence(
    			$model,
    			$model->{$this->column} + $add
    		);
    	}
    }

    protected function setSequence(Model $model, int $sequence)
    {
    	$model->update([
			$this->column => $sequence,
		]);

		return $sequence + 1;
    }

    protected function resetTheSequenceToNormalNumber(Model $model, array $resequenced, $sequence = 1000)
    {
    	$resource_class = $this->getResourceClass($model);
		$models = $resource_class::whereNotIn('id', $resequenced)->orderBy($this->column, $this->direction)->get();
		$this->setSequenceOnModels($models, $sequence);
    }

    protected function setSequenceOnModels(Collection $models, $sequence, $add = 0)
    {
    	foreach ($models as $model) {
    		$this->setSequence(
    			$model,
    			$sequence
    		);
    		$sequence++;
    	}
    }
}
