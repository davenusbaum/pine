***

# Parameters

Parameters extends ArrayMap with methods specific to input filtering



* Full name: `\Pine\Parameters`
* Parent class: [`\Pine\ArrayMap`](./ArrayMap.md)






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

### __construct

Create a new collection object

```php
public __construct(array $array = null): mixed
```








**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$array` | **array** | optional array passed by value |





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


***
> Automatically generated on 2024-11-12
