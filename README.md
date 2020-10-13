# RESPHP
a library for encoding/decoding the redis [resp protocol](https://redis.io/topics/protocol)

---
### install

using [composer](https://github.com/chaseisabelle/resphp)
```bash
composer install chaseisabelle/resphp
```

---
### encoding

```php
$encoded = \ChaseIsabelle\RESPHP\Encoder::encode([
    'simple string',
    'bulk string¡',
    1,
    null, 
    []
]);
```

---
### decoding

```php
$decoded = \ChaseIsabelle\RESPHP\Decoder::decode($encoded);
```
