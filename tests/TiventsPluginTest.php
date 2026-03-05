<?php
/**
 * Integration tests for the TIVENTS Products Feed plugin.
 *
 * Requires a full WordPress test environment (see bin/install-wp-tests.sh).
 * Run with: ./vendor/bin/phpunit --configuration phpunit.integration.xml
 */

class TiventsPluginTest extends WP_UnitTestCase
{
    public function test_plugin_version_constant_is_defined(): void
    {
        $this->assertTrue(defined('TIVENTPRO_CURRENT_VERSION'));
    }

    public function test_plugin_version_matches_header(): void
    {
        $this->assertEquals('2.0.4', TIVENTPRO_CURRENT_VERSION);
    }

    public function test_shortcode_tivents_products_is_registered(): void
    {
        $this->assertTrue(shortcode_exists('tivents_products'));
    }

    public function test_shortcode_tivents_sponsorships_is_registered(): void
    {
        $this->assertTrue(shortcode_exists('tivents_sponsorships'));
    }

    public function test_shortcode_output_without_partner_id_shows_error(): void
    {
        update_option('tivents_partner_id', null);

        $output = do_shortcode('[tivents_products]');

        $this->assertNotEmpty($output);
        $this->assertStringContainsString('Partner ID', $output);
    }

    public function test_rest_route_is_registered(): void
    {
        $routes = rest_get_server()->get_routes();
        $this->assertArrayHasKey('/tivents/calendar/v1/events', $routes);
    }
}
