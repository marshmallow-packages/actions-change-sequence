![alt text](https://cdn.marshmallow-office.com/media/images/logo/marshmallow.transparent.red.png "marshmallow.")

# MrMallow
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
