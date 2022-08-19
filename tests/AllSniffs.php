<?php
/**
 * A test class for testing all sniffs for installed standards.
 *
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @copyright 2006-2015 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   https://github.com/squizlabs/PHP_CodeSniffer/blob/master/licence.txt BSD Licence
 */

namespace Innocode\CodingStandards\Tests;

use PHP_CodeSniffer\Autoload;
use PHP_CodeSniffer\Util\Standards;
use PHPUnit\Framework\TestSuite;
use PHPUnit\TextUI\TestRunner;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Class AllSniffs
 */
class AllSniffs {

	const TEST_SUFFIX = 'UnitTest.php';

	/**
	 * Prepare the test runner.
	 *
	 * @return void
	 */
	public static function main() {
		TestRunner::run( self::suite() );
	}

	/**
	 * Add all sniff unit tests into a test suite.
	 *
	 * Sniff unit tests are found by recursing through the 'Tests' directory
	 * of each installed coding standard.
	 *
	 * @return \PHPUnit\Framework\TestSuite
	 */
	public static function suite() {
		$GLOBALS['PHP_CODESNIFFER_SNIFF_CODES']   = [];
		$GLOBALS['PHP_CODESNIFFER_FIXABLE_CODES'] = [];

		$suite = new TestSuite( 'Innocode Standards' );

		$standards_dir = dirname( __DIR__ ) . '/Innocode';
		$all_details   = Standards::getInstalledStandardDetails( false, $standards_dir );
		$details       = $all_details['Innocode'];

		Autoload::addSearchPath( $details['path'], $details['namespace'] );

		$test_dir = $details['path'] . '/Tests/';

		if ( is_dir( $test_dir ) === false ) {
			return $suite;
		}

		$di = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $test_dir ) );

		foreach ( $di as $file ) {
			$filename = $file->getFilename();

			if ( substr( $filename, 0, 1 ) === '.' ) {
				continue;
			}

			if ( substr( $filename, -1 * strlen( static::TEST_SUFFIX ) ) !== static::TEST_SUFFIX ) {
				continue;
			}

			$class_name = Autoload::loadFile( $file->getPathname() );
			$GLOBALS['PHP_CODESNIFFER_STANDARD_DIRS'][ $class_name ] = $details['path'];
			$GLOBALS['PHP_CODESNIFFER_TEST_DIRS'][ $class_name ]     = $test_dir;
			$suite->addTestSuite( $class_name );
		}

		return $suite;
	}
}
