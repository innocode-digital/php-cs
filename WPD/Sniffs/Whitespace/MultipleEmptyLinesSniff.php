<?php
/**
 * Check multiple consecutive newlines in a file.
 */

namespace WPD\Sniffs\Whitespace;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class MultipleEmptyLinesSniff implements Sniff {

	/**
	 * Registers the tokens that this sniff wants to listen for.
	 *
	 * @return array
	 */
	public function register(): array {
		return [
			T_WHITESPACE,
		];
	}

	/**
	 * Called when one of the token types that this sniff is listening for
	 * is found.
	 *
	 * The stackPtr variable indicates where in the stack the token was found.
	 * A sniff can acquire information this token, along with all the other
	 * tokens within the stack by first acquiring the token stack:
	 *
	 * @param \PHP_CodeSniffer\Files\File $phpcs_file The PHP_CodeSniffer file where the
	 *                                               token was found.
	 * @param int                         $stack_ptr  The position in the PHP_CodeSniffer
	 *                                               file's token stack where the token
	 *                                               was found.
	 */
	public function process( File $phpcs_file, $stack_ptr ) {
		$tokens = $phpcs_file->getTokens();

		if (
			$stack_ptr <= 2
			|| $tokens[ $stack_ptr - 1 ]['line'] >= $tokens[ $stack_ptr ]['line']
			|| $tokens[ $stack_ptr - 2 ]['line'] !== $tokens[ $stack_ptr - 1 ]['line']
		) {
			return;
		}

		$next = $phpcs_file->findNext(
			T_WHITESPACE,
			$stack_ptr,
			null,
			true
		);

		if ( $tokens[ $next ]['line'] - $tokens[ $stack_ptr ]['line'] > 1 ) {
			$is_fixed = $phpcs_file->addFixableError(
				'Multiple empty lines should not exist in a row; found %s consecutive empty lines',
				$stack_ptr,
				'MultipleEmptyLines',
				[ $tokens[ $next ]['line'] - $tokens[ $stack_ptr ]['line'] ]
			);

			if ( $is_fixed === true ) {
				$phpcs_file->fixer->beginChangeset();
				$index = $stack_ptr;

				while ( $tokens[ $index ]['line'] !== $tokens[ $next ]['line'] ) {
					$phpcs_file->fixer->replaceToken( $index, '' );
					$index++;
				}

				$phpcs_file->fixer->addNewlineBefore( $index );
				$phpcs_file->fixer->endChangeset();
			}
		}
	}
}
