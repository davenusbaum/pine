***

# Route

A route that was matched by the router



* Full name: `\Pine\Route`



## Properties


### stack



```php
public array $stack
```






***

### params



```php
public array $params
```






***

### path



```php
public string $path
```






***

### req



```php
protected \Pine\Request|null $req
```






***

### res



```php
protected \Pine\Response|null $res
```






***

## Methods


### run

Run a request and response through this route

```php
public run(\Pine\Request $req, \Pine\Response $res): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$req` | **\Pine\Request** |  |
| `$res` | **\Pine\Response** |  |





***

### __invoke

Invoke the next handler for this route

```php
public __invoke(): mixed
```












***


***
> Automatically generated on 2024-11-12
