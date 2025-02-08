***

# Application

Application represents the web application



* Full name: `\Pine\Application`



## Properties


### router



```php
public \Pine\Router $router
```






***

### settings



```php
protected \Pine\ArrayMap $settings
```






***

## Methods


### __construct

Construct an Application object

```php
public __construct(array $tree = []): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$tree` | **array** | An optional precompiled router tree |





***

### get

Get an application setting

```php
public get(string $name): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** | The setting name |





***

### handle

Handle a request

```php
public handle(array $server, array $get, array $post, array $cookie): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$server` | **array** | the $_SERVER array |
| `$get` | **array** | the $_GET array |
| `$post` | **array** | the $_POST array |
| `$cookie` | **array** | The $_COOKIE array |





***

### set

Set an application setting

```php
public set(string $name, mixed $value): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** |  |
| `$value` | **mixed** |  |





***


***
> Automatically generated on 2024-11-12
