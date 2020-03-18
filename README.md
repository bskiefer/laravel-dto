# laravel-dto
Data Transfer Objects complete with castable attributes and validation.

## Example
```php
<?php

namespace Domain\DTO\Requests;

use DataTransferObject\DataTransferObject;

/**
 * Class PricesRequest.
 */
class PricesRequest extends DataTransferObject
{

    /**
     * @var string
     */
    public $serviceCode;
    /**
     * @var string
     */
    public $effectiveDate;
    /**
     * @var int
     */
    public $quantity;

    protected $casts = [
        'serviceCode' => 'string',
        'quantity' => 'int',
    ];

    protected $dates = [
        'effectiveDate'
    ];

    public function getEffectiveDateAttribute($date)
    {
        return $date->format($this->getDateFormat());
    }

    // OR

    public function getEffectiveDateAttribute()
    {
        return $this->getOriginal('effectiveDate')->format($this->getDateFormat());
    }
}

```

## Usage

### Mutators

```php
class User extends DataTransferObject
{
    ...
        
    public function getNameAttribute($value)
    {
        return ucwords($value);
    }

    // OR

    public function getNameAttribute()
    {
        return ucwords($this->getOriginal('name'));
    }
}
```

```php

echo $array['name'];
// john smith

$user = new User($array);

echo $user->name; // John Smith
echo $user['name']; // John Smith
echo $user->getAttribute('name'); // John Smith
echo $user->getOriginal('name'); // john smith
```

### Types

Cast variables to other DTOs automatically

```php
class PostData extends DataTransferObject
{
    /** @var string */
    public $title;
    
    /** @var string|null */
    public $body;
    
    /** @var App\DataTransferObjects\Author */
    public $author;
    
    /** @var App\DataTransferObjects\Tag[] */
    public $tags;
}
```

```php
$postData = new $postData($array);

$postData->author; // Instance of App\DataTransferObjects\Author
$postData->tags; // Array of App\DataTransferObjects\Tag
```

### Helpers

```php
$postData->all();

$postData
    ->only('title', 'body')
    ->toArray();
    
$postData
    ->except('author')
    ->toArray();

$postData->toCollection();
```