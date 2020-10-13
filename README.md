# RESPHP
a library for encoding/decoding the redis [resp protocol](https://redis.io/topics/protocol)

---
### install

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
