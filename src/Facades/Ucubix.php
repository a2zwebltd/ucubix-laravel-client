<?php

declare(strict_types=1);

namespace Ucubix\LaravelClient\Facades;

use Illuminate\Support\Facades\Facade;
use Ucubix\PhpClient\Client\UcubixClient;
use Ucubix\PhpClient\Dto\LicenseKey;
use Ucubix\PhpClient\Dto\Order;
use Ucubix\PhpClient\Dto\Organisation;
use Ucubix\PhpClient\Dto\PaginatedResponse;
use Ucubix\PhpClient\Dto\Product;

/**
 * @method static Organisation getOrganisation()
 * @method static PaginatedResponse getProducts(array $filters = [], int $page = 1, int $perPage = 15, ?string $sort = null)
 * @method static Product getProduct(string $id)
 * @method static PaginatedResponse getProductPhotos(string $id, int $page = 1, int $perPage = 15)
 * @method static PaginatedResponse getProductScreenshots(string $id, int $page = 1, int $perPage = 15)
 * @method static PaginatedResponse getProductCategories(string $id, int $page = 1, int $perPage = 15)
 * @method static PaginatedResponse getProductPublishers(string $id, int $page = 1, int $perPage = 15)
 * @method static PaginatedResponse getProductPlatforms(string $id, int $page = 1, int $perPage = 15)
 * @method static PaginatedResponse getProductFranchises(string $id, int $page = 1, int $perPage = 15)
 * @method static PaginatedResponse getProductDevelopers(string $id, int $page = 1, int $perPage = 15)
 * @method static PaginatedResponse getOrders(array $filters = [], int $page = 1, int $perPage = 15, string $sort = '-order_date')
 * @method static Order getOrder(string $id)
 * @method static PaginatedResponse getOrderItems(string $orderId, int $page = 1, int $perPage = 15)
 * @method static Order createOrder(string $productUuid, int $quantity, string $regionCode, ?string $countryCode = null, ?string $externalReference = null)
 * @method static Order updateOrder(string $id, int $quantity)
 * @method static bool cancelOrder(string $id)
 * @method static LicenseKey getLicenseKey(string $id)
 * @method static LicenseKey[] getBulkLicenseKeys(array $ids)
 * @method static PaginatedResponse getCategories(int $page = 1, int $perPage = 15, ?string $sort = null)
 * @method static PaginatedResponse getPublishers(int $page = 1, int $perPage = 15, ?string $sort = null)
 * @method static PaginatedResponse getPlatforms(int $page = 1, int $perPage = 15, ?string $sort = null)
 * @method static PaginatedResponse getDevelopers(int $page = 1, int $perPage = 15, ?string $sort = null)
 * @method static PaginatedResponse getFranchises(int $page = 1, int $perPage = 15, ?string $sort = null)
 * @method static UcubixClient setMaxRetryOnRateLimit(int $max)
 * @method static int getMaxRetryOnRateLimit()
 * @method static ?int getRateLimitRemaining()
 * @method static ?int getRateLimitLimit()
 *
 * @see UcubixClient
 */
class Ucubix extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return UcubixClient::class;
    }
}
