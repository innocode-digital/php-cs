<?php

namespace WPD\Sniffs\Security;

use PHP_CodeSniffer\Files\File;
use WordPressCS\WordPress\Sniffs\Security\NonceVerificationSniff as WPCSNonceVerificationSniff;

class NonceVerificationSniff extends WPCSNonceVerificationSniff {

	/**
	 * Initialize the class for the current process.
	 *
	 * This is overridden from the parent class to allow $_GET because
	 * query variables are typically used for non-destructive actions.
	 *
	 * @param \PHP_CodeSniffer\Files\File $phpcs_file The file currently being processed.
	 */
	public function init( File $phpcs_file ) {
		parent::init( $phpcs_file );

		unset( $this->superglobals['$_GET'] );
	}
}
