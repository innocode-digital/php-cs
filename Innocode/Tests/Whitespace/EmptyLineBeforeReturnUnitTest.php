<?php

namespace Innocode\Tests\Whitespace;

use PHP_CodeSniffer\Tests\Standards\AbstractSniffUnitTest;

/**
 * Class EmptyLineBeforeReturnUnitTest
 */
class EmptyLineBeforeReturnUnitTest extends AbstractSniffUnitTest {

	/**
	 * Returns the lines where errors should occur.
	 *
	 * @return array <int line number> => <int number of errors>
	 */
	public function getErrorList() {
		$file = func_get_arg( 0 );

		if ( $file === 'EmptyLineBeforeReturnUnitTest.success' ) {
			return [];
		}

		return [
			11 => 1,
			24 => 1,
			30 => 1,
		];
	}

	/**
	 * Returns the lines where warnings should occur.
	 *
	 * @return array <int line number> => <int number of warnings>
	 */
	public function getWarningList(): array {
		return [];
	}
}
