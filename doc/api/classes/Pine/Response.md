***

# Response

An HTTP response to an HTTP request



* Full name: `\Pine\Response`



## Properties


### app



```php
public \Pine\Application $app
```






***

### req



```php
private \Pine\Request $req
```






***

### locals



```php
public \Pine\ArrayMap $locals
```






***

## Methods


### __construct

Create a new response object

```php
public __construct(mixed $request): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$request` | **mixed** |  |





***

### end

Clear the route and ends processing of the

```php
public end(): mixed
```












***

### json

Send the body as json.

```php
public json(mixed $json): mixed
```

The content-type is set to application/json






**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$json` | **mixed** |  |





***

### redirect

Send a redirect to the client

```php
public redirect(string $to, int $status = null): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$to` | **string** |  |
| `$status` | **int** |  |





***

### render

Render the specified page for the current scope

```php
public render(string $page): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$page` | **string** |  |





***

### status

Sets the HTTP status for the response.

```php
public status(int $code, mixed $message = null): \Pine\Response
```

This is a chainable  statusCode().






**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$code` | **int** |  |
| `$message` | **mixed** |  |





***


***
> Automatically generated on 2024-11-12
