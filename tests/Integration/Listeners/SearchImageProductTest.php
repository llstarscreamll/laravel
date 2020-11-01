<?php

namespace Tests\Integration\Listeners;

use App\Events\ProductImageProcessed;
use App\Listeners\SearchImageProduct;
use Illuminate\Events\CallQueuedListener;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Kirby\Products\Models\Product;
use Tests\TestCase;

class SearchImageProductTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function shouldBeCalledWhenProductCreatedEventIsFired()
    {
        Queue::fake();

        $product = factory(Product::class)->create();
        event(new ProductImageProcessed($product));

        Queue::assertPushed(CallQueuedListener::class, function ($job) {
            return $job->class == SearchImageProduct::class;
        });
    }

    /**
     * @test
     */
    public function shouldAttachImagesToProductWhenImagesExistAndProductExists()
    {
        $product = factory(Product::class)->create();
        $event = new ProductImageProcessed($product->code);

        @mkdir(storage_path("app/public/images/products/sm/"), 0777, true);
        @mkdir(storage_path("app/public/images/products/md/"), 0777, true);
        @mkdir(storage_path("app/public/images/products/lg/"), 0777, true);

        @touch(storage_path("app/public/images/products/sm/{$product->code}.jpg"));
        @touch(storage_path("app/public/images/products/md/{$product->code}.jpg"));
        @touch(storage_path("app/public/images/products/lg/{$product->code}.jpg"));

        app(SearchImageProduct::class)->handle($event);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'sm_image_url' => Storage::disk('public-local-images')->url("sm/{$product->code}.jpg"),
            'md_image_url' => Storage::disk('public-local-images')->url("md/{$product->code}.jpg"),
            'lg_image_url' => Storage::disk('public-local-images')->url("lg/{$product->code}.jpg"),
        ]);
    }

    /**
     * @test
     */
    public function shouldNotUpdateProductImagesUrlsWhenImagesDoesNotExist()
    {
        $product = factory(Product::class)->create(['sm_image_url' => null, 'md_image_url' => null, 'lg_image_url' => null]);
        $event = new ProductImageProcessed($product);

        // in case random images still exist
        @unlink(storage_path("app/public/images/sm/{$product->code}.jpg"));
        @unlink(storage_path("app/public/images/md/{$product->code}.jpg"));
        @unlink(storage_path("app/public/images/lg/{$product->code}.jpg"));

        app(SearchImageProduct::class)->handle($event);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'sm_image_url' => $product->sm_image_url,
            'md_image_url' => $product->md_image_url,
            'lg_image_url' => $product->lg_image_url,
        ]);
    }

    public function shouldNotAttachImagesToProductWhenProductDoesNotExists()
    {
        $code = 'fake-code';
        $event = new ProductImageProcessed($code);

        // in case random images still exist
        @touch(storage_path("app/public/images/sm/{$code}.jpg"));
        @touch(storage_path("app/public/images/md/{$code}.jpg"));
        @touch(storage_path("app/public/images/lg/{$code}.jpg"));

        app(SearchImageProduct::class)->handle($event);

        // no product should exist with said images
        $this->assertDatabaseMissing('products', [
            'sm_image_url' => Storage::disk('public-local-images')->url("sm/{$code}.jpg"),
            'md_image_url' => Storage::disk('public-local-images')->url("md/{$code}.jpg"),
            'lg_image_url' => Storage::disk('public-local-images')->url("lg/{$code}.jpg"),
        ]);
    }
}
