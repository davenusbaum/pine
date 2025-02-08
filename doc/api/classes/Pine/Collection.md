***

# Collection

Abstract class that provides a basic object wrapper for an array



* Full name: `\Pine\Collection`
* Parent class: [`\Pine\AbstractArray`](./AbstractArray.md)
* **Warning:** this class is **deprecated**. This means that this class will likely be removed in a future version.



## Properties


### array



```php
protected array $array
```






***

## Methods


### __construct

Create a new collection object

```php
public __construct(array $array = null, mixed& $array_reference = null): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$array` | **array** | optional array passed by value |
| `$array_reference` | **mixed** |  |





***

### __get

Magic method to return a collection item as a property.

```php
public __get(mixed $name): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **mixed** |  |





***

### __set

Magic method to set a collection item as a property.

```php
public __set(mixed $name, mixed $value): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **mixed** |  |
| `$value` | **mixed** |  |





***

### add

Add an item to the end of the list

```php
public add(mixed $item): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$item` | **mixed** |  |





***

### addAll

Add all of the items in a list to this list

```php
public addAll(array $list): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$list` | **array** |  |





***

### has

If true if the named value is set for the collection

```php
public has(string $name): bool
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** |  |





***

### get

Returns the value for the specified name.

```php
public get(string $name, mixed $default = null): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** |  |
| `$default` | **mixed** |  |





***

### remove

Remove a named value from the collection

```php
public remove(mixed $name): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **mixed** |  |





***

### set

Set a value in the collection

```php
public set(string $name, mixed $value): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$name` | **string** |  |
| `$value` | **mixed** |  |





***


## Inherited methods


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
