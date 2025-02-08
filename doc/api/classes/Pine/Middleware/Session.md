***

# Session





* Full name: `\Pine\Middleware\Session`
* Parent class: [`ArrayMap`](../../pine/ArrayMap.md)



## Properties


### active



```php
public $active
```






***

## Methods


### __construct

Start a session to store persistent attributes

```php
public __construct(): bool
```












***

### __invoke



```php
public __invoke(\pine\Request $req, \pine\Response $res, callable $next): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$req` | **\pine\Request** |  |
| `$res` | **\pine\Response** |  |
| `$next` | **callable** |  |





***

### clear

Clear the current context.

```php
public clear(): mixed
```












***

### getId

Returns the session id.

```php
public getId(): mixed
```

Returns null when there is no session, unlike session_id()
which returns an empty string.










***

### destroy

Invalidate the session for this context.

```php
public destroy(): mixed
```












***

### isActive

Returns true if the session is active

```php
public static isActive(): bool
```



* This method is **static**.








***


***
> Automatically generated on 2024-11-12
