<?php

namespace Marshmallow\Nova\Actions\Sequence;

use Illuminate\Bus\Queueable;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\InteractsWithQueue;
use Marshmallow\Nova\Actions\Sequence\Traits\SequenceHelper;

class SequenceLast extends Action
{
    use InteractsWithQueue, Queueable, SequenceHelper;

    public $name = 'Place at the bottom';

    /**
     * The text to be used for the action's confirm button.
     *
     * @var string
     */
    public $confirmButtonText = 'Change sequence';

    /**
     * The text to be used for the action's confirmation text.
     *
     * @var string
     */
    public $confirmText = 'Are you sure you want to put this item (these items) at the bottom?';
    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
    	$resource_class = $this->getResourceClass($models->first());
    	$all_models = $resource_class::orderBy($this->column, $this->direction)->get();

    	$this->setSequenceOnModels(
    		$models,
    		$all_models->count() + 1000
    	);

    	$all_models = $resource_class::orderBy($this->column, $this->direction)->get();
    	$this->setSequenceOnModels($all_models, 1);
    }
}
