<?php

namespace Marshmallow\Nova\Actions\Sequence\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

trait SequenceHelper
{
	protected $column;

	protected $groupBy;

	protected $direction;

	public function __construct($direction = 'asc', $column = 'sequence', $groupBy = null)
    {
    	$this->direction = strtolower($direction);
    	$this->column = $column;
    	$this->groupBy = $groupBy;
    }

    protected function oppositeDirection()
    {
    	return ($this->direction == 'asc') ? 'desc' : 'asc';
    }

	protected function getResourceClass(Model $model)
    {
    	return get_class($model);
    }

    protected function getBaseQueryForOrdering($model)
    {
    	if ($model instanceof Collection) {
    		$model = $model->first();
    	}

    	$resource_class = $this->getResourceClass($model);
    	$query = $resource_class::orderBy($this->column, $this->direction);
    	if ($this->groupBy) {
    		$query->where($this->groupBy, $model->{$this->groupBy});
    	}

    	return $query;
    }

    protected function getAllModelsOrdered(Collection $models)
    {
    	$query = $this->getBaseQueryForOrdering($models);
    	return $query->get();
    }

    protected function changeSequenceWithLargeNumber(Model $model, $add = 1000)
    {
    	$query = $this->getBaseQueryForOrdering($model);
    	$models = $query->get();
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
    	$query = $this->getBaseQueryForOrdering($model);
    	$models = $query->whereNotIn('id', $resequenced)->get();
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
