<?php
class TiventsPluginTest extends WP_UnitTestCase {
    public function test_plugin_activated() {
        $this->assertTrue(is_plugin_active('tivents-wordpress-plugin/tivents-wordpress-plugin.php'));
    }

    public function test_shortcode_output() {
        $output = do_shortcode('[tivents_products]');
        $this->assertNotEmpty($output);
        $this->assertStringContainsString('TIVENTS', $output);
    }
}
