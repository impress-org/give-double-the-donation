<?php

namespace GiveConstantContact\Tests;

use Give\Tests\TestCase;
use GiveDoubleTheDonation\Addon\Environment;

/**
 * @unreleased
 */
class TestGiveDoubleTheDonation extends TestCase
{
    /**
     * @unreleased
     */
    public function testReadMeVersionMatchesPluginVersion(): void
    {
        $readme = file_get_contents( trailingslashit(GIVE_DTD_DIR) . 'readme.txt' );
        preg_match('/Stable tag:(.*)/i', $readme, $stableTag);

        $this->assertEquals( GIVE_DTD_VERSION, trim($stableTag[1]));
    }

    /**
     * @unreleased
     */
    public function testHasMinimumGiveWPVersion(): void
    {
        $this->assertSame('3.7.0', GIVE_DTD_MIN_GIVE_VERSION);
    }

    /**
     * @unreleased
     */
    public function testIsCompatibleWithGiveWP(): void
    {
        $this->assertTrue(Environment::giveMinRequiredVersionCheck());
    }
}
