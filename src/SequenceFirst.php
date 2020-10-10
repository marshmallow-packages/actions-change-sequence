<?php

namespace Marshmallow\Nova\Actions\Sequence;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Marshmallow\Nova\Actions\Sequence\Traits\SequenceHelper;

class SequenceFirst extends Action
{
    use InteractsWithQueue, Queueable, SequenceHelper;

    public $name = 'Place at the top';

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
    public $confirmText = 'Are you sure you want to put this item (these items) first?';

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $this->changeSequenceWithLargeNumber(
            $models->first()
        );

        /**
    	 * Set the correct sequence on the provided models.
    	 */
        $sequence = 1;
        $resequenced = [];
        foreach ($models->fresh() as $model) {
            $this->setSequence(
                $model,
                $sequence
            );

            $resequenced[] = $model->id;
            $sequence++;
        }

        $this->resetTheSequenceToNormalNumber(
            $models->first(),
            $resequenced,
            $sequence
        );
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}
