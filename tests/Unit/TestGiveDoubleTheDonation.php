<?php

namespace GiveConstantContact\Tests;

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
        $readme = file_get_contents( trailingslashit(GIVE_DTD_DIR) . 'readme.txt' );
        preg_match('/Stable tag:(.*)/i', $readme, $stableTag);

        $this->assertEquals( GIVE_DTD_VERSION, trim($stableTag[1]));
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
}
