# MFCollectionsBundle

## Map

Implements: `ArrayAcces, IteratorAggregate, Countable`
Can have associated keys

## List

Implements: `ArrayAcces, IteratorAggregate, Countable`
Have just values

## Bundle for Symfony2 which allows to use Collections as follows:

It's basicly a syntax sugar over and classic array structure, which allows you to use it as classic array, but adds some cool features.

### Usage:

```
$map = new Map();
$map->set(1, 'one');
$map[2] = 'two';

$map->toArray(); // [1 => 'one', 2 => 'two']

$map->filter('($k, $v) => $k > 1')->map('($k, $v) => $k . " - " . $v')->toArray(); // [2 => '2 - two']

//against classic PHP

$array = [1 => 'one', 2 => 'two'];

array_map(function($k, $v) {return $k . ' - ' . $v;}, array_filter(function($k, $v) {return $k > 1;}));
```

# How does it work?

It parse function from string and evaluate it with `eval()`
