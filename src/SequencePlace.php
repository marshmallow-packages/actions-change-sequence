<?php

namespace Marshmallow\Nova\Actions\Sequence;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Number;
use Marshmallow\Nova\Actions\Sequence\Traits\SequenceHelper;
use Laravel\Nova\Http\Requests\NovaRequest;

class SequencePlace extends Action
{
    use InteractsWithQueue, Queueable, SequenceHelper;

    public $name = 'Place in location';

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
    public $confirmText = 'Are you sure you want to change the sequence for these items?';

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $model_ids = $models->pluck('id')->toArray();
        $offset = ($models->first()->{$this->column} < $fields->place) ? 1 : 2;
        /**
         * First, make room to add the new resource placement
         */
        $all_models = $this->getAllModelsOrdered($models);
        $sequence = ($models->count()) + 1;
        foreach ($all_models as $model) {
            $this->setSequence(
                $model,
                $model->{$this->column} + $sequence
            );
            $sequence += ($models->count()) + 1;
        }



        /**
         * Next, place the resources in the correct place.
         */
        if ($fields->place == 1) {
            $this->setSequenceOnModels($models, 1);
        } else {
            $resource_class = $this->getResourceClass($models->first());
            $after_this = $resource_class::orderBy($this->column, $this->direction)
                ->offset($fields->place - $offset)
                ->limit(1)
                ->first();

            if (!$after_this) {
                $after_this = $resource_class::orderBy($this->column, $this->oppositeDirection($this->direction))
                    ->limit(1)
                    ->first();
            }

            $sequence = $after_this->{$this->column} + 1;
            foreach ($models->fresh() as $model) {
                $this->setSequence(
                    $model,
                    $sequence
                );
                $sequence++;
            }

            // $sequence_updated = false;
            // $all_models = $this->getAllModelsOrdered($models);
            // $sequence = 1;

            // foreach ($all_models as $loop_count => $model) {
            // 	if ($loop_count == ($fields->place - 1)) {
            // 		$sequence_updated = true;
            // 		foreach ($models as $_model) {
            // 			$this->setSequence(
            //  			$_model,
            //  			$sequence
            //  		);

            //  		$sequence++;
            // 		}
            // 	} else {
            // 		if (in_array($model->id, $model_ids)) {
            // 			continue;
            // 		}
            // 		$this->setSequence(
            // 			$model,
            // 			$sequence
            // 		);

            // 		$sequence++;
            // 	}
            // }

            /**
             * Update the sequence if a place was entered
             * that is larger then the total amount of
             * resources available.
             */
            // if ($sequence_updated === false) {
            // 	$this->setSequenceOnModels($models, $model->sequence + 1);
            // }
        }


        /**
         * Now reset the order so the numbers 1,2,3
         */
        $this->setSequenceOnModels(
            $this->getAllModelsOrdered($models),
            1
        );
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            Number::make('Place')
                ->help('Where do you want this resource to be placed in the sequence?'),
        ];
    }
}
