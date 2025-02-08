***

# Request

An HTTP request



* Full name: `\Pine\Request`



## Properties


### app



```php
public \Pine\Application $app
```






***

### body

The body of the request

```php
public \Pine\Parameters $body
```






***

### cookies



```php
public \Pine\Parameters $cookies
```






***

### params



```php
public \Pine\Parameters $params
```






***

### query

The query parameters

```php
public \Pine\Parameters $query
```






***

### res



```php
public \Pine\Response $res
```






***

### route



```php
public \Pine\Route $route
```






***

### props



```php
private array $props
```






***

### server



```php
private array $server
```






***

### timestamp



```php
private float $timestamp
```






***

## Methods


### __construct

Create a new request object

```php
public __construct(\Pine\Application $app, array $server, array $get, array $post, array $cookie): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$app` | **\Pine\Application** | The application handling the request |
| `$server` | **array** |  |
| `$get` | **array** |  |
| `$post` | **array** |  |
| `$cookie` | **array** |  |





***

### __get

Magic method to get a property

```php
public __get(string $name): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** |  |





***

### __set

Magic method to set a property

```php
public __set(string $name, mixed $value): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** |  |
| `$value` | **mixed** |  |





***

### get

Return the named request header.

```php
public get(string $name, string $default = null): string
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** |  |
| `$default` | **string** |  |





***

### getBasePath



```php
protected getBasePath(): string
```












***

### getBaseUrl



```php
protected getBaseUrl(): string
```












***

### getHost



```php
protected getHost(): string
```












***

### getHostname



```php
protected getHostname(): string
```












***

### getIp

Returns the remote client IP address

```php
protected getIp(): string|null
```












***

### getIps

If there is trusted proxy and an X-Forwarded-For header, the method returns
the address in the X-Forwarded-For header minus the trusted proxies.

```php
protected getIps(): array
```

If there is no trusted proxy, the REMOTE_ADDR is the only IP returned.










***

### getMethod



```php
protected getMethod(): string
```












***

### getOriginalUrl



```php
protected getOriginalUrl(): string
```












***

### getPath



```php
protected getPath(): string
```












***

### getPort

Returns the PORT that received the request

```php
protected getPort(): int|null
```












***

### getProtocol



```php
protected getProtocol(): string
```












***

### getSecure



```php
protected getSecure(): bool
```












***

### getScriptName



```php
protected getScriptName(): string
```












***

### getTrustProxy

Returns true if the proxy can be trusted

```php
protected getTrustProxy(): bool
```












***


***
> Automatically generated on 2024-11-12
