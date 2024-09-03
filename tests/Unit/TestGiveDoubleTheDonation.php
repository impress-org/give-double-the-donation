<?php

namespace GiveDoubleTheDonation\Tests;

use Give\Tests\TestCase;
use GiveDoubleTheDonation\Addon\Environment;

/**
 * @since 2.0.0
 */
class TestGiveDoubleTheDonation extends TestCase
{
    /**
     * @since 2.0.0
     */
    public function testReadMeVersionMatchesPluginVersion(): void
    {
        $readme = get_file_data(
            trailingslashit(GIVE_DTD_DIR) . "readme.txt",
            [
                "Version" => "Stable tag"
            ]
        );

        $plugin = get_plugin_data(GIVE_DTD_FILE);

        $this->assertEquals(GIVE_DTD_VERSION, $readme['Version']);
        $this->assertEquals(GIVE_DTD_VERSION, $plugin['Version']);
        $this->assertEquals($readme['Version'], $plugin['Version']);
    }

    /**
     * @since 2.0.0
     */
    public function testHasMinimumGiveWPVersion(): void
    {
        $this->assertSame('3.8.0', GIVE_DTD_MIN_GIVE_VERSION);
    }

    /**
     * @since 2.0.0
     */
    public function testIsCompatibleWithGiveWP(): void
    {
        $this->assertTrue(Environment::giveMinRequiredVersionCheck());
    }

     /**
     * @since 2.0.0
     */
    public function testReadMeRequiresPHPVersionMatchesPluginVersion(): void
    {
        $readme = get_file_data(
            trailingslashit(GIVE_DTD_DIR) . "readme.txt",
            [
                "RequiresPHP" => "Requires PHP"
            ]
        );

        $plugin = get_plugin_data(GIVE_DTD_FILE);

        $this->assertEquals($plugin['RequiresPHP'], $readme['RequiresPHP']);
    }

    /**
     * @since 2.0.0
     */
    public function testReadMeRequiresWPVersionMatchesPluginHeaderVersion(): void
    {
        $readme = get_file_data(
            trailingslashit(GIVE_DTD_DIR) . "readme.txt",
            [
                "RequiresWP" => "Requires at least"
            ]
        );

        $plugin = get_plugin_data(GIVE_DTD_FILE);

        $this->assertEquals($plugin['RequiresWP'], $readme['RequiresWP']);
    }
}
