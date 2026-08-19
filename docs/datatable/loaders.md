---
title: Loaders
weight: 4
---

With the introduction of Livewire 3, there are several new methods available for use:

## Loading Placeholder
```php
public function configure(): void
{
  $this->setLoadingPlaceholderBlade('');
}
```

### setLoadingPlaceholderStatus
```php
public function configure(): void
{
  $this->setLoadingPlaceholderStatus(true);
}
```

### setLoadingPlaceholderEnabled
```php
public function configure(): void
{
  $this->setLoadingPlaceholderEnabled();
}
```


### setLoadingPlaceholderDisabled
```php
public function configure(): void
{
  $this->setLoadingPlaceholderDisabled();
}
```

### setLoadingPlaceholderContent
```php
public function configure(): void
{
  $this->setLoadingPlaceholderContent('');
}
```

### setLoadingPlaceHolderAttributes
```php
public function configure(): void
{
  $this->setLoadingPlaceHolderAttributes([]);
}
```

### setLoadingPlaceHolderIconAttributes
```php
public function configure(): void
{
  $this->setLoadingPlacehosetLoadingPlaceHolderIconAttributeslderBlade([]);
}
```

### setLoadingPlaceHolderWrapperAttributes
```php
public function configure(): void
{
  $this->setLoadingPlaceHolderWrapperAttributes([]);
}
```


### setLoadingPlaceholderBlade
```php
public function configure(): void
{
  $this->setLoadingPlaceholderBlade('');
}
```


## Lazy Loading

Tables support Livewire's `lazy` attribute out of the box:

```blade
<livewire:pets-table lazy />
```

The default placeholder is an empty element carrying the table's Alpine scope, which is what stops Alpine throwing `ReferenceError` while the real table is being loaded in. To show a skeleton instead, override `placeholder()` on your table and keep that scope:

```php
public function placeholder(): string
{
    return view('tables.pets-skeleton', ['scope' => $this->getAlpineFallbackScope()])->render();
}
```

```blade
{{-- tables/pets-skeleton.blade.php --}}
<div x-data="{{ $scope }}">
    <div class="animate-pulse h-64 bg-gray-100 dark:bg-gray-700 rounded-md"></div>
</div>
```

## Lazy Placeholder (Fork Feature)

The fork provides a built-in skeleton placeholder with proper Alpine.js scope, supporting both Tailwind CSS and Bootstrap. No manual `placeholder()` override needed.

### Basic Usage

Simply add `lazy="on-load"` to your Livewire component tag:

```html
<livewire:my-table lazy="on-load" />
```

The default skeleton placeholder is automatically rendered — no configuration needed.

### setLazyPlaceholderStatus
```php
public function configure(): void
{
  $this->setLazyPlaceholderStatus(true);
}
```

### setLazyPlaceholderEnabled
Enables the lazy placeholder (enabled by default).
```php
public function configure(): void
{
  $this->setLazyPlaceholderEnabled();
}
```

### setLazyPlaceholderDisabled
Disables the lazy placeholder content. The Alpine.js scope wrapper is still rendered to prevent errors, but no skeleton content is shown.
```php
public function configure(): void
{
  $this->setLazyPlaceholderDisabled();
}
```

### setLazyPlaceholderView
Set a custom Blade view to use as the placeholder content. Your custom view will be wrapped with the required Alpine.js scope automatically.
```php
public function configure(): void
{
  $this->setLazyPlaceholderView('components.my-custom-skeleton');
}
```

### Overriding the placeholder Method
For full control, you may override the `placeholder()` method directly:
```php
public function placeholder(array $params = []): \Illuminate\Contracts\View\View
{
  return view('my-fully-custom-placeholder');
}
```
**Note:** If you override `placeholder()` entirely, you are responsible for including the Alpine.js `x-data` scope on the root element to prevent morph errors.
