![alt text](https://cdn.marshmallow-office.com/media/images/logo/marshmallow.transparent.red.png "marshmallow.")

# Laravel Nova Change Sequence
Update the sequences on your resource in Laravel Nova. This package currently supports:
- [x] Place at the top
- [x] Place at the bottom
- [x] Place in location
<!-- - [ ] x places higher -->
<!-- - [ ] x places lower -->

<img src="https://gitlab.com/marshmallow-packages/nova/actions/change-sequence/-/raw/master/resources/screenshots/options.png">

### Installatie
```bash
composer require marshmallow/actions-change-sequence
```

### Usage
```php
public function actions(Request $request)
{
    return array_merge([
	    //
    ], SequenceActions::make());
}
```

If you want to sequence a group op items in a resources you can use the `group()` method. For instance; if you have a table with invoice items and you want to change the order of the items on a invoice, you really need to change the sequence of only the items in a certant invoice, not the whole table. See the example below.

```php
public function actions(Request $request)
{
    return array_merge([
	    //
    ], SequenceActions::groupBy('invoice_id')->make());
}
```

By default we will use a sequence `ascending` and check for the column `sequence`. You can override this in the constructor:
```php

SequenceActions::make('asc', 'sequence');

new SequenceFirst('desc', 'order_column');

```

Optionaly, you can add every action manualy. We don't recommend this. If you use the shorthand above, this will make sure you will directly profit of new actions added in the future.
```php
public function actions(Request $request)
{
    return [
        new SequenceFirst,
        new SequenceLast,
        new SequencePlace,
    ];
}
```
