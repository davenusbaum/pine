***

# Flash





* Full name: `\Pine\Middleware\Flash`
* Parent class: [`Collection`](../../Nusbaum/Pine/Collection.md)


## Constants

| Constant | Visibility | Type | Value |
|:---------|:-----------|:-----|:------|
|`FLASH_KEY`|public| |&#039;flashMessages&#039;|


## Methods


### __invoke

A generator to be added to a pine route.

```php
public __invoke(\Nusbaum\Pine\Request $req, \Nusbaum\Pine\Response $res, mixed $next): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$req` | **\Nusbaum\Pine\Request** |  |
| `$res` | **\Nusbaum\Pine\Response** |  |
| `$next` | **mixed** |  |





***

### flash

Add a flash message to the message list

```php
public flash(string $msg, \Pine\Middleware\number $type = 1, string $field = null): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$msg` | **string** |  |
| `$type` | **\Pine\Middleware\number** |  |
| `$field` | **string** |  |





***

### flashSuccess

Add a success message to the message list

```php
public flashSuccess(string $msg): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$msg` | **string** |  |





***

### flashInfo

Add an info message to the message list

```php
public flashInfo(string $msg): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$msg` | **string** |  |





***

### flashWarning

Add a warning message to the message list

```php
public flashWarning(string $msg): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$msg` | **string** |  |





***

### flashError

Add an error message to the message list

```php
public flashError(string $msg, mixed $field = null): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$msg` | **string** |  |
| `$field` | **mixed** |  |





***


***
> Automatically generated on 2024-11-12
