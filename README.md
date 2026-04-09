# UCubix Laravel Client

Laravel wrapper for the [UCubix PHP Client](https://github.com/a2zwebltd/ucubix-php-client) — provides a service provider, facade, and config file for seamless Laravel integration. No duplicated business logic.

## Requirements

- PHP 8.2+
- Laravel 10, 11, 12, or 13

## Installation

```bash
composer require ucubix/laravel-client
```

The service provider and facade are auto-discovered. No manual registration needed.

## Configuration

Publish the config file:

```bash
php artisan vendor:publish --tag=ucubix-config
```

This creates `config/ucubix.php`. All settings are configurable via environment variables:

```env
UCUBIX_API_KEY=your-api-key
UCUBIX_BASE_URL=https://ucubix.com/api/v1/
UCUBIX_MAX_RETRY=3
```

| Key | Env Variable | Default | Description |
|---|---|---|---|
| `api_key` | `UCUBIX_API_KEY` | `''` | Bearer token for API authentication |
| `base_url` | `UCUBIX_BASE_URL` | `https://ucubix.com/api/v1/` | API base URL |
| `max_retry_on_rate_limit` | `UCUBIX_MAX_RETRY` | `3` | Max retries on 429 responses |

## Quick Start

```php
use Ucubix\LaravelClient\Facades\Ucubix;

// Get organisation info
$org = Ucubix::getOrganisation();
echo $org->name;

// Search products
$products = Ucubix::getProducts(['search' => 'Cyberpunk']);
foreach ($products->data as $product) {
    echo "{$product->name} ({$product->type})\n";
}

// Get single product with pricing
$product = Ucubix::getProduct('product-uuid');
foreach ($product->regional_pricing as $region) {
    echo "{$region->region_code}: {$region->reseller_wsp} WSP\n";
}

// Create order
$order = Ucubix::createOrder('product-uuid', 1, 'EU', 'DE');
echo "Order {$order->code} — {$order->status}\n";

// Get license keys
$items = Ucubix::getOrderItems($order->id);
foreach ($items->data as $item) {
    if ($item->hasLicenseKey()) {
        $key = Ucubix::getLicenseKey($item->license_key_uuid);
        echo "Key: {$key->license_key}\n";
    }
}
```

## Dependency Injection

```php
use Ucubix\PhpClient\Client\UcubixClient;

class ProductController extends Controller
{
    public function index(UcubixClient $client)
    {
        $products = $client->getProducts(page: 1, perPage: 50);
        
        return response()->json($products->toArray());
    }
}
```

The `UcubixClient` is registered as a singleton — the same instance is reused across the request lifecycle.

## Facade Reference

The `Ucubix` facade proxies all methods directly to `UcubixClient`. All DTOs extend `Spatie\LaravelData\Data` and support `toArray()`, `toJson()`, etc.

### Organisation

| Method | Returns |
|---|---|
| `Ucubix::getOrganisation()` | `Organisation` |

### Products

| Method | Returns |
|---|---|
| `Ucubix::getProducts(filters, page, perPage, sort)` | `PaginatedResponse<Product>` |
| `Ucubix::getProduct(id)` | `Product` |
| `Ucubix::getProductPhotos(id, page, perPage)` | `PaginatedResponse<Media>` |
| `Ucubix::getProductScreenshots(id, page, perPage)` | `PaginatedResponse<Media>` |
| `Ucubix::getProductCategories(id, page, perPage)` | `PaginatedResponse<Category>` |
| `Ucubix::getProductPublishers(id, page, perPage)` | `PaginatedResponse<Publisher>` |
| `Ucubix::getProductPlatforms(id, page, perPage)` | `PaginatedResponse<Platform>` |
| `Ucubix::getProductFranchises(id, page, perPage)` | `PaginatedResponse<Franchise>` |
| `Ucubix::getProductDevelopers(id, page, perPage)` | `PaginatedResponse<Developer>` |

**Product filters:** `search`, `category`, `publisher`, `developer`, `franchise`, `platform`

### Orders

| Method | Returns |
|---|---|
| `Ucubix::getOrders(filters, page, perPage, sort)` | `PaginatedResponse<Order>` |
| `Ucubix::getOrder(id)` | `Order` |
| `Ucubix::getOrderItems(orderId, page, perPage)` | `PaginatedResponse<OrderItem>` |
| `Ucubix::createOrder(productUuid, quantity, regionCode, countryCode?)` | `Order` |
| `Ucubix::updateOrder(id, quantity)` | `Order` |
| `Ucubix::cancelOrder(id)` | `bool` |

**Order filters:** `code`, `external_reference`

### License Keys

| Method | Returns |
|---|---|
| `Ucubix::getLicenseKey(id)` | `LicenseKey` |
| `Ucubix::getBulkLicenseKeys(ids)` | `LicenseKey[]` |

### Catalog Dictionaries

| Method | Returns |
|---|---|
| `Ucubix::getCategories(page, perPage, sort)` | `PaginatedResponse<Category>` |
| `Ucubix::getPublishers(page, perPage, sort)` | `PaginatedResponse<Publisher>` |
| `Ucubix::getPlatforms(page, perPage, sort)` | `PaginatedResponse<Platform>` |
| `Ucubix::getDevelopers(page, perPage, sort)` | `PaginatedResponse<Developer>` |
| `Ucubix::getFranchises(page, perPage, sort)` | `PaginatedResponse<Franchise>` |

### Rate Limiting

| Method | Returns | Description |
|---|---|---|
| `Ucubix::getRateLimitRemaining()` | `?int` | Server-reported remaining requests |
| `Ucubix::getRateLimitLimit()` | `?int` | Server-reported limit |
| `Ucubix::setMaxRetryOnRateLimit(max)` | `UcubixClient` | Set max 429 retries |
| `Ucubix::getMaxRetryOnRateLimit()` | `int` | Get max 429 retries |

## Pagination

All list endpoints return `PaginatedResponse<T>`:

```php
$page = 1;
do {
    $products = Ucubix::getProducts(page: $page, perPage: 50);

    foreach ($products->data as $product) {
        // process
    }

    $page++;
} while ($products->hasMorePages());
```

## Error Handling

All exceptions from [ucubix/php-client](https://github.com/a2zwebltd/ucubix-php-client) propagate as-is:

```php
use Ucubix\PhpClient\Exceptions\ApiException;
use Ucubix\PhpClient\Exceptions\AuthenticationException;
use Ucubix\PhpClient\Exceptions\RateLimitException;
use Ucubix\PhpClient\Exceptions\ValidationException;

try {
    $order = Ucubix::createOrder($uuid, 5, 'InvalidRegion');
} catch (AuthenticationException $e) {
    // 401/403 — invalid API key or IP not whitelisted
} catch (ValidationException $e) {
    // 422 — validation failed
    echo $e->field;
} catch (RateLimitException $e) {
    // 429 — all retries exhausted
    echo $e->retryAfter;
} catch (ApiException $e) {
    // All other API errors
    echo $e->errorDetail;
}
```

## Rate Limiting

The underlying PHP client has a dual-layer rate limiting system:

1. **Client-side sliding window** — proactive throttling (default: 100 req/min)
2. **Server-side 429 retry** — reactive, respects `Retry-After` header (default: 3 retries)

The `UCUBIX_MAX_RETRY` config controls the retry count. The sliding window adapts automatically if the server reports a higher limit.

For full rate limiting documentation, see [ucubix/php-client](https://github.com/a2zwebltd/ucubix-php-client#rate-limiting).

## DTOs

All DTOs are from `ucubix/php-client` and extend `Spatie\LaravelData\Data`:

```php
$product = Ucubix::getProduct('uuid');

$product->toArray();  // array
$product->toJson();   // JSON string

// Works seamlessly in responses
return response()->json($product);
```

For full DTO documentation, see [ucubix/php-client DTOs](https://github.com/a2zwebltd/ucubix-php-client#dtos).

## Testing

```bash
composer install
vendor/bin/phpunit
```

## License

MIT. See [LICENSE](LICENSE).
