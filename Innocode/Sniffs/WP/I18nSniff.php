<?php

namespace Innocode\Sniffs\WP;

use WordPressCS\WordPress\Sniffs\WP\I18nSniff as WPCSI18nSniff;

class I18nSniff extends WPCSI18nSniff {

	/**
	 * Check if supplied tokens represent a translation text string literal.
	 *
	 * This is overridden to allow TEXT_DOMAIN and CHILD_TEXT_DOMAIN constants
	 * to be used in place of __( '', 'text-domain' ).
	 *
	 * @param array $context Context of the tokens.
	 * @return bool
	 */
	protected function check_argument_tokens( $context ) {
		$tokens   = $context['tokens'];
		$arg_name = $context['arg_name'];
		$content  = isset( $tokens[0] ) ? $tokens[0]['content'] : '';

		if ( $arg_name === 'domain' && in_array( $content, [ 'TEXT_DOMAIN', 'CHILD_TEXT_DOMAIN' ], true ) ) {
			return true;
		}

		return parent::check_argument_tokens( $context );
	}
}
