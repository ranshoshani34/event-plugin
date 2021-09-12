<?php


class General_Tests extends WP_UnitTestCase {
	public function test_is_scripts_enqueued() {
		global $wp_scripts;
		self::assertSameSets(['jquery', 'event_scripts', 'calendar_scripts'], $wp_scripts->queue);
	}

	public function test_is_styles_enqueued() {
		self::assertTrue(wp_style_is('style.css', 'enqueued'));
	}

}
