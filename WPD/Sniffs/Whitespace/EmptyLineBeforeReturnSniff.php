<?php
/**
 * Check empty line before returns in a file.
 */

namespace WPD\Sniffs\Whitespace;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Util\Tokens;

class EmptyLineBeforeReturnSniff implements Sniff {

	/**
	 * Registers the tokens that this sniff wants to listen for.
	 *
	 * @return array
	 */
	public function register(): array {
		return [
			T_RETURN,
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
		$tokens   = $phpcs_file->getTokens();
		$previous = $phpcs_file->findPrevious(
			Tokens::$emptyTokens, // phpcs:ignore
			$stack_ptr - 1,
			null,
			true
		);

		if (
			$tokens[ $stack_ptr ]['line'] - $tokens[ $previous ]['line'] === 1
			&& $tokens[ $previous ]['type'] !== 'T_OPEN_CURLY_BRACKET'
		) {
			$is_fixed = $phpcs_file->addFixableError(
				'Add empty line before return statement in %d line.',
				$stack_ptr,
				'AddEmptyLineBeforeReturnStatement',
				[ $tokens[ $stack_ptr ]['line'] ]
			);

			if ( $is_fixed === true ) {
				$phpcs_file->fixer->addNewline( $previous );
			}
		} elseif (
			$tokens[ $stack_ptr ]['line'] - $tokens[ $previous ]['line'] > 1
			&& $tokens[ $previous ]['type'] === 'T_OPEN_CURLY_BRACKET'
		) {
			$is_fixed = $phpcs_file->addFixableError(
				'Remove empty line before return statement in %d line.',
				$stack_ptr,
				'RemoveEmptyLineBeforeReturnStatement',
				[ $tokens[ $previous ]['line'] + 1 ]
			);

			if ( $is_fixed === true ) {
				$phpcs_file->fixer->replaceToken( $previous + 1, '' );
			}
		}
	}
}
