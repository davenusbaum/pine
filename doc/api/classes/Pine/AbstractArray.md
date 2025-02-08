***

# AbstractArray

Abstract class that provides a basic object wrapper for an array



* Full name: `\Pine\AbstractArray`
* This class implements:
[`\ArrayAccess`](../ArrayAccess.md), [`\Iterator`](../Iterator.md)
* This class is an **Abstract class**



## Properties


### array



```php
protected $array
```






***

## Methods


### count

Returns the number of elements in the underlying array

```php
public count(): int
```












***

### current

Return the current element

```php
public current(): mixed
```












***

### key

Return the key of the current element

```php
public key(): mixed
```












***

### next

Move forward to next element

```php
public next(): void
```












***

### offsetExists

Whether an offset exists

```php
public offsetExists(mixed $offset): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$offset` | **mixed** |  |





***

### offsetGet

Offset to retrieve

```php
public offsetGet(mixed $offset): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$offset` | **mixed** |  |





***

### offsetSet

Assign a value to the specified offset

```php
public offsetSet(mixed $offset, mixed $value): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$offset` | **mixed** |  |
| `$value` | **mixed** |  |





***

### offsetUnset

Unset an offset

```php
public offsetUnset(mixed $offset): void
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$offset` | **mixed** |  |





***

### rewind

Rewind the Iterator to the first element

```php
public rewind(): void
```












***

### toArray

Returns the collection as an array

```php
public toArray(): array
```












***

### valid

Checks if current position is valid

```php
public valid(): bool
```












***


***
> Automatically generated on 2024-11-12
