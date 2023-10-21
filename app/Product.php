<?php

namespace App;

use Illuminate\Support\Str;
use App\Scopes\EnabledScope;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use NodeTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];

    /**
     * @var array
     */
    protected $with = ['images'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'inventory_status' => 'json',
    ];

    /**
     * Voyager Additional attributes
     *
     * @var array
     */
    public $additional_attributes = [
        'fulfillment_fee',
        'total_cost',
    ];

    /**
     * @var array
     */
    protected $appends = [
        'main_image',
        'original_price',
        'price_each',
        'price_size',
        'shipping_code',
        'available_inventory_warehouse',
        'total_cost',
        'fulfillment_fee',
        'text_description'
    ];

    /**
     * Searchable columns
     *
     * @var array
     */
    protected $searchableColumns = [
        'searchable_text', 'name', 'id', 'upc', 'sku', 'description'
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new EnabledScope);

        static::addGlobalScope(function ($query) {
            return $query->where('products.price', '>', 0);
        });
    }

    /**
     * Get main image
     *
     * @return string
     */
    public function getMainImageAttribute()
    {
        if ($this->images->isEmpty()) {
            return 'storage/' . config('default-variables.default-image');
        }

        $image = $this->images->sortByDesc('is_main')->first();

        if (Storage::disk('public')->exists($image->src)) {
            try {
                return \Bkwld\Croppa\Facade::url(
                    str_replace(['storage//'], 'storage/', 'storage/' . $image->src), 
                    300,
                    300,
                    ['resize']
                );
            } catch (\Exception $e) {
                return $image->src;
            }
        }

        $imageLink = ( is_null($image) || ! Storage::disk('public')->exists('products/productImages/' . $image->src))
                    ? 'storage/' . config('default-variables.default-image')
                    : 'storage/products/productImages/' .  $image->src;

        try {
            return \Bkwld\Croppa\Facade::url('/' . $imageLink, 300, 300, ['resize']);
        } catch (\Exception $e) {
            return 'storage/' . config('default-variables.default-image');
        }
    }

    /**
     * Get original price (marketing price)
     *
     * @return float
     */
    public function getOriginalPriceAttribute()
    {
        return $this->attributes['price'] * 1.24;
    }

    /**
     * Description attribute
     *
     * @return string
     */
    public function getTextDescriptionAttribute()
    {
        return html_entity_decode(
            str_replace(
                ['<p></p>', '<b><p><h1>', '</b></h1></p>', '<p><h1>', '</h1></p>', '<br>'],
                ['', '<p class="mb-1">', '</p>', '<p class="mb-1">', '</p>', ''],
                trim($this->attributes['description'])
            )
        );
    }

    /**
     * Get inventory list
     *
     * @return array
     */
    public function getInventoryStatusAttribute()
    {
        try {
            if (! isset($this->attributes['inventory_status'])) {
                return [];
            }

            $response = json_decode($this->attributes['inventory_status'] ?? '{}', true);

            return is_array($response) || is_object($response)
                ? $response
                : [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * @return Collection
     */
    public function getAvailableInventoryWarehouseAttribute()
    {
        try {
            return collect(
                $this->getInventoryStatusAttribute()
            )
            ->where('status', 'in stock')
            ->values();
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Get shipping code for this product
     *
     * @param  string $zipcode
     * @return string
     */
    public function getShippingCodeAttribute($zipcode = null) : string
    {
        if (! isset($this->attributes['frozen']) || (isset($this->attributes['frozen']) && (int) $this->attributes['frozen'] === 0)) {
            return 'R02';
        }

        try {

            if ($this->getAvailableInventoryWarehouseAttribute()->isEmpty()) {
                return 'F11';
            }

            if (! isset($zipcode) || is_null($zipcode)) {
                return 'F11';
            }

            $zipcode = explode('-', $zipcode)[0] ?? '';

            $zipcode = Zipcode::where('name', $zipcode)
                ->remember(60 * 60 * 60)
                ->firstOrFail();

            $zipcodeWarehouses = $zipcode->transitTimeList->sortBy('time')
                ->where('time', '<', 3)
                ->where('time', '>', 0)
                ->values();

            $availableWarehouses = $this->getAvailableInventoryWarehouseAttribute()->pluck('warehouse')
                ->intersect(
                    $zipcodeWarehouses->pluck('warehouse')
                );

            if (! $availableWarehouses->isEmpty()) {
                return 'R02';
            }

        } catch (\Exception $e) {

        }

        return 'F11';
    }


    /**
     * Calculate the shipping cost of a product
     *
     * @return float
     */
    public function getShippingCostAttribute()
    {
        if ((isset($this->attributes['free_shipping']) && $this->attributes['free_shipping'] === 1) || $this->attributes['weight'] === 0 || $this->attributes['weight'] === null) {
            return 0;
        }

        $perPound = config('default-variables.' . ($this->frozen ? 'frozen' : 'regular') . '.per_pound', 0);
        return $this->attributes['weight'] * $perPound;
    }

    /**
     * @return string
     */
    public function getGoogleProductCategoryAttribute()
    {
        $googleProductCategory = isset($this->attributes['google_product_category']) && trim($this->attributes['google_product_category']) !== ''
            ? $this->attributes['google_product_category']
            : 'Business & Industrial > Food Service';

        if (trim($googleProductCategory) === '') {
            $googleProductCategory = 'Business & Industrial > Food Service';
        }

        return $googleProductCategory;
    }

    /**
     * Get Price per each
     *
     * @return float
     */
    public function getPriceEachAttribute()
    {
        $pack = 0;

        if (isset($this->attributes['pack']) || isset($this->pack)) {
            $pack = $this->attributes['pack'] ?? $this->pack ?? 0;
        }

        return (int) $pack > 0
            ? round($this->attributes['price'] / $pack, 2)
            : $this->attributes['price'];
    }

    /**
     * Get Price per size
     *
     * @return float
     */
    public function getPriceSizeAttribute()
    {
        $size = $this->attributes['size'] ?? $this->size ?? 0;
        $pack = $this->attributes['pack'] ?? $this->pack ?? 0;

        return ((int) $size > 0) && ((int) $pack > 0)
            ? round($this->attributes['price'] / ($size * $pack), 2)
            : $this->attributes['price'];
    }

    /**
     * Get weight uom attribute
     *
     * @return float
     */
    public function getWeightUomAttribute()
    {
        $value = '';

        if (isset($this->attributes['weight_uom']) || isset($this->weight_uom)) {
            $value = $this->attributes['weight_uom'] ?? $this->weight_uom ?? '';
        }

        if (trim($value) === '') {
            return 'Lbs';
        }

        return $value;
    }

    /**
     * Get frozen attribute
     *
     * @return float
     */
    public function getFrozenAttribute()
    {
        $isFrozen = isset($this->attributes['frozen']) ? $this->attributes['frozen'] : 0;

        return (int) $isFrozen === 1;
    }

    /**
     * Get Amazon handling time
     *
     * @return string
     */
    public function getLatencyAttribute()
    {
        try {

            $isFrozen = isset($this->attributes['frozen']) ? $this->attributes['frozen'] : 0;

            if (! $isFrozen) {
                return setting('amazon.regular_latency');
            }

            $day = strtolower(
                now()->format('l')
            );

            return setting("amazon.frozen_latency_{$day}");

        } catch (\Exception $e) {
            return 3;
        }
    }

    /**
     * Set slug attribute
     *
     * @param string $value
     */
    public function setSlugAttribute($value)
    {
        if (trim($value) === '') {
            $value = $this->attributes['name'] ?? $this->attributes['id'] ?? strtotime('now');
        }

        if (isset($this->attributes['name'])) {
            $this->attributes['searchable_text'] = $this->attributes['name'];
        }

        $this->attributes['slug'] = Str::slug($value);
    }

    /**
     * @param mixed $value
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim($value);
        $this->attributes['searchable_text'] = trim(html_entity_decode(strip_tags($value)));
    }

    /**
     * @param mixed $value
     */
    public function setSearchableTextAttribute($value)
    {
        try {
            $this->attributes['searchable_text'] = trim(html_entity_decode(strip_tags($value)));

            if ($this->attributes['searchable_text'] === '' && isset($this->attributes['name'])) {
                $this->attributes['searchable_text'] = trim($this->attributes['name']);
            }

            if ($this->attributes['searchable_text'] === '' && isset($this->attributes['description'])) {
                $this->attributes['searchable_text'] = trim(html_entity_decode(strip_tags($this->attributes['description'])));
            }
        } catch (\Exception $e) {

        }
    }

    /**
     * Get Price per size
     *
     * @return float
     */
    public function setSizeUomAttribute($value)
    {
        $value = trim($value);

        switch (strtoupper($value)) {

            case 'LB':
                $this->attributes['size_uom'] = 'Pound';
                break;

            case 'CNT':
                $this->attributes['size_uom'] = 'Unit';
                break;

            case 'OZ':
                $this->attributes['size_uom'] = 'Ounce';
                break;

            case 'QT':
                $this->attributes['size_uom'] = 'Quart';
                break;

            default:
                $this->attributes['size_uom'] = $value;
                break;
        }
    }

    /**
     * Product availability
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function availability()
    {
        return $this->belongsTo(Availability::class);
    }

    /**
     * Product availability
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function scrapped()
    {
        return $this->hasOne(ProductScrapped::class)
            ->remember(self::getDefaultCacheTime());
    }

    /**
     * Product brand
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class)
                    ->withDefault()
                    ->remember(self::getDefaultCacheTime());
    }

    /**
     * Product manufacture
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manufacture()
    {
        return $this->belongsTo(Manufacture::class)
                    ->remember(self::getDefaultCacheTime());
    }

    /**
     * Product country
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class)
                    ->remember(self::getDefaultCacheTime());
    }

    /**
     * Relation to the parent.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Product', $this->getParentIdName())
            ->setModel($this)
            ->withoutGlobalScopes([EnabledScope::class])
            ->remember(self::getDefaultCacheTime());
    }

    /**
     * Relation to children.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\Product', $this->getParentIdName())
            ->setModel($this)
            ->withoutGlobalScopes([EnabledScope::class])
            ->remember(self::getDefaultCacheTime());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function nutrition()
    {
        return $this->hasOne(ProductNutrition::class)
            ->remember(self::getDefaultCacheTime());
    }

    /**
     * Product images
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class)
                    ->remember(self::getDefaultCacheTime());
    }

     /**
     * User Lists
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userLists()
    {
        return $this->belongsToMany(UserList::class);
    }

    /**
     * Product reviews
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews()
    {
        return $this->hasMany(Review::class)->remember(self::getDefaultCacheTime());
    }

    /**
     * Product orders
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class)
                    ->withPivot(['quantity', 'options', 'price', 'discount'])
                    ->remember(self::getDefaultCacheTime());
    }

    /**
     * Product categories
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class)
                    ->remember(self::getDefaultCacheTime());
    }

    public function quotes()
    {
        return $this->belongsToMany(Product::class, 'quote_product');
    }

    public function quote_product(Quote $quote)
    {
        return QuoteProduct::where('product_id', $this->id)
            ->where('quote_id', $quote->id)
            ->first();
    }

    /**
     * @return float
     */
    public function getCostAttribute()
    {
        return round(($this->attributes['cost'] ?? 0), 2);
    }

    /**
     * @return float
     */
    public function getFulfillmentFeeAttribute()
    {
        if ($this->getFrozenAttribute()) {
            return config('default-variables.frozen.fulfillment_fee');
        }

        return config('default-variables.regular.fulfillment_fee');
    }

    /**
     * @return float
     */
    public function getTotalCostAttribute()
    {
        return $this->getCostAttribute() + $this->getFulfillmentFeeAttribute();
    }

    /**
     * @return float
     */
    public function getPriceAttribute()
    {
        return round(($this->attributes['price'] ?? 0), 2);
    }

    /**
     * Product categories
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function getCategoryAttribute()
    {
        try {
            return $this->categories()->orderBy('id', 'desc')->remember(self::getDefaultCacheTime())->get()->first();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @return collection
     */
    public function getBoughtTogetherAttribute()
    {
        if (! $this->exists) {
            return collect();
        }

        if ($this->scrapped instanceof ProductScrapped && ! $this->scrapped->bought_together->isEmpty()) {
            return $this->scrapped->bought_together;
        }

        $id = $this->id;

        return Order::with('products')
            ->has('products', '>=', 2)
            ->whereHas('products', function($query) use ($id) {
                $query->where('product_id', $id);
            })
            ->remember(60 * 60 * 60)
            ->get()
            ->map(function($order) {
                return $order->products;
            })
            ->flatten()
            ->where('id', '!=', $id)
            ->unique('id')
            ->values();
    }

    /**
     * Product parent categories
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function getParentCategoriesAttribute()
    {
        if ($this->category instanceof Category) {
            return Category::remember(config('default-variables.cache_life_time'))
                ->ancestorsAndSelf($this->category);
        }

        return collect();
    }

    /**
     * Get FBA
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function scopeFba($query)
    {
        return $query->where('is_fba', true);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  mixed $searchTerm
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $searchTerm)
    {
        $terms = array_map(function($value) {
                return trim(strtolower(strip_tags($value)));
            },
                explode(' ', strip_tags(trim(strtolower($searchTerm))))
            );

        $searchableColumns = $this->searchableColumns;

        return $query
            ->where(function($query) use ($searchTerm, $terms, $searchableColumns) {
                return $query->where(function($query) use ($searchTerm, $terms, $searchableColumns){

                    for ($i = 0; $i < count($terms) - 1; $i++) {

                        foreach (['searchable_text', 'name'] as $column) {

                            $query = $query->orWhere(function($query) use ($terms, $column) {

                                $query = $query->orWhereRaw('LOWER('.$column.') LIKE ? ', ['%' . implode('%', $terms) . '%']);

                                $singularTerms = array_map(function($term){
                                    return Str::singular($term);
                                }, $terms);

                                $pluralTerms = array_map(function($term){
                                    return Str::plural($term);
                                }, $terms);

                                $query = $query->orWhereRaw('LOWER('.$column.') LIKE ? ', ['%' . implode('%', $singularTerms) . '%']);
                                $query = $query->orWhereRaw('LOWER('.$column.') LIKE ? ', ['%' . implode('%', $pluralTerms) . '%']);
                            });
                        }

                        shuffle($terms);
                    }

                    foreach ($searchableColumns as $column) {

                        $value = trim(strtolower(strip_tags($searchTerm)));
                        $query = $query->orWhereRaw('LOWER('.$column.') LIKE ? ', ['%'.$value.'%']);

                        if (in_array($column, ['searchable_text', 'name'])) {
                            $query = $query->orWhereRaw('LOWER('.$column.') LIKE ? ', ['%'.Str::singular($value).'%']);
                            $query = $query->orWhereRaw('LOWER('.$column.') LIKE ? ', ['%'.Str::plural($value).'%']);
                        }
                    }
                });
            })
            ->orderByRaw("
                CASE WHEN instr(LOWER(searchable_text), '?') = 0 THEN 1 ELSE 0 END,
                instr(LOWER(searchable_text), '?') DESC,
                CHAR_LENGTH(searchable_text) DESC
            ", [trim(strtolower($searchTerm)), trim(strtolower($searchTerm))]);
    }
}
