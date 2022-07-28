<?php

namespace Innocode\Sniffs\Security;

use WordPressCS\WordPress\Sniffs\Security\EscapeOutputSniff as WPCSEscapeOutputSniff;

class EscapeOutputSniff extends WPCSEscapeOutputSniff {

	/**
	 * Printing functions that incorporate unsafe values.
	 *
	 * This is overridden from the parent class to allow _e() and
	 * _ex() functions.
	 *
	 * @var array
	 */
	protected $unsafePrintingFunctions = []; // phpcs:ignore
}
