<?php
/**
 * Unit tests for Tivents_Product_Controller.
 *
 * These tests run without WordPress – only pure PHP logic is exercised.
 */

use PHPUnit\Framework\TestCase;

class ProductsTest extends TestCase
{
    // -----------------------------------------------------------------------
    // tivents_get_product_url()
    // -----------------------------------------------------------------------

    public function testGetProductUrl_ReturnsOpeningAnchorTag(): void
    {
        $result = Tivents_Product_Controller::tivents_get_product_url('https://example.com/product/1');
        $this->assertStringStartsWith('<a ', $result);
    }

    public function testGetProductUrl_ContainsHrefWithUtm(): void
    {
        $result = Tivents_Product_Controller::tivents_get_product_url('https://example.com/product/1');
        $this->assertStringContainsString(
            'href="https://example.com/product/1?utm=tivents-wordpress-plugin"',
            $result
        );
    }

    public function testGetProductUrl_HasCorrectId(): void
    {
        $result = Tivents_Product_Controller::tivents_get_product_url('https://example.com');
        $this->assertStringContainsString('id="tivents-product-link"', $result);
    }

    // -----------------------------------------------------------------------
    // set_image_url()
    // -----------------------------------------------------------------------

    public function testSetImageUrl_WithCdnKey_ReturnsCdnUrl(): void
    {
        $product = [
            'cdn_image_key' => 'events/summer-2024.jpg',
            'image_url'     => 'https://fallback.example.com/img.jpg',
        ];

        $url = Tivents_Product_Controller::set_image_url($product);

        $this->assertEquals('https://cdn.tivents.io/events/summer-2024.jpg', $url);
    }

    public function testSetImageUrl_WithNullCdnKey_ReturnsFallbackUrl(): void
    {
        $product = [
            'cdn_image_key' => null,
            'image_url'     => 'https://fallback.example.com/img.jpg',
        ];

        $url = Tivents_Product_Controller::set_image_url($product);

        $this->assertEquals('https://fallback.example.com/img.jpg', $url);
    }

    // -----------------------------------------------------------------------
    // tivents_set_product_time()
    // -----------------------------------------------------------------------

    public function testSetProductTime_WithDateField_ReturnsDateField(): void
    {
        $product = [
            'date'  => '15.06.2024',
            'start' => '2024-06-15 10:00:00',
            'end'   => '2024-06-15 18:00:00',
        ];

        $date = Tivents_Product_Controller::tivents_set_product_time($product);

        $this->assertEquals('15.06.2024', $date);
    }

    public function testSetProductTime_WithNullDate_FormatsFromStartEnd(): void
    {
        $product = [
            'date'  => null,
            'start' => '2024-06-15 10:00:00',
            'end'   => '2024-06-15 18:00:00',
        ];

        $date = Tivents_Product_Controller::tivents_set_product_time($product);

        $this->assertStringContainsString('15.06.2024', $date);
        $this->assertStringContainsString(' - ', $date);
    }

    public function testSetProductTime_WithNullDate_IncludesTime(): void
    {
        $product = [
            'date'  => null,
            'start' => '2024-06-15 10:00:00',
            'end'   => '2024-06-15 18:00:00',
        ];

        $date = Tivents_Product_Controller::tivents_set_product_time($product);

        $this->assertStringContainsString('10:00', $date);
        $this->assertStringContainsString('18:00', $date);
    }

    // -----------------------------------------------------------------------
    // create_product_card()
    // -----------------------------------------------------------------------

    private function makeSampleProduct(array $overrides = []): array
    {
        return array_merge([
            'short_url'     => 'https://tiv.example.com/p/42',
            'cdn_image_key' => null,
            'image_url'     => 'https://img.example.com/photo.jpg',
            'date'          => '01.01.2025',
            'start'         => null,
            'end'           => null,
            'name'          => 'Test Event',
            'place'         => 'Berlin',
        ], $overrides);
    }

    public function testCreateProductCard_ContainsProductName(): void
    {
        $html = Tivents_Product_Controller::create_product_card($this->makeSampleProduct());
        $this->assertStringContainsString('Test Event', $html);
    }

    public function testCreateProductCard_ContainsPlace(): void
    {
        $html = Tivents_Product_Controller::create_product_card($this->makeSampleProduct());
        $this->assertStringContainsString('Berlin', $html);
    }

    public function testCreateProductCard_ContainsProductLink(): void
    {
        $html = Tivents_Product_Controller::create_product_card($this->makeSampleProduct());
        $this->assertStringContainsString('utm=tivents-wordpress-plugin', $html);
        $this->assertStringContainsString('</a>', $html);
    }

    public function testCreateProductCard_ContainsCdnImage(): void
    {
        $product = $this->makeSampleProduct(['cdn_image_key' => 'events/hero.jpg']);
        $html    = Tivents_Product_Controller::create_product_card($product);
        $this->assertStringContainsString('https://cdn.tivents.io/events/hero.jpg', $html);
    }

    public function testCreateProductCard_ContainsFallbackImage(): void
    {
        $html = Tivents_Product_Controller::create_product_card($this->makeSampleProduct());
        $this->assertStringContainsString('https://img.example.com/photo.jpg', $html);
    }

    // -----------------------------------------------------------------------
    // create_product_unstyled_card()
    // -----------------------------------------------------------------------

    public function testCreateProductUnstyledCard_ContainsProductName(): void
    {
        $html = Tivents_Product_Controller::create_product_unstyled_card($this->makeSampleProduct());
        $this->assertStringContainsString('Test Event', $html);
    }

    public function testCreateProductUnstyledCard_ContainsPlace(): void
    {
        $html = Tivents_Product_Controller::create_product_unstyled_card($this->makeSampleProduct());
        $this->assertStringContainsString('Berlin', $html);
    }

    public function testCreateProductUnstyledCard_ContainsImageWithAlt(): void
    {
        $html = Tivents_Product_Controller::create_product_unstyled_card($this->makeSampleProduct());
        $this->assertStringContainsString('alt="Test Event - Image"', $html);
    }

    public function testCreateProductUnstyledCard_ContainsLink(): void
    {
        $html = Tivents_Product_Controller::create_product_unstyled_card($this->makeSampleProduct());
        $this->assertStringContainsString('utm=tivents-wordpress-plugin', $html);
        $this->assertStringContainsString('</a>', $html);
    }
}
