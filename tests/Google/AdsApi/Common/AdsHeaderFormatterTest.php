<?php
/**
 * Copyright 2016 Google Inc. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace Google\AdsApi\Common;

use Google\AdsApi\Common\Testing\ApplicationNames;
use PHPUnit_Framework_TestCase;

/**
 * Tests for `AdsHeaderFormatter`.
 *
 * @see AdsHeaderFormatter
 * @small
 */
class AdsHeaderFormatterTest extends PHPUnit_Framework_TestCase {

  private $applicationNames;
  private $adsHeaderFormatter;

  /**
   * @see PHPUnit_Framework_TestCase::setUp
   */
  protected function setUp() {
    $this->applicationNames = new ApplicationNames();
    $adsUtilityRegistry = $this->getMockBuilder(AdsUtilityRegistry::class)
        ->disableOriginalConstructor()
        ->getMock();
    $adsUtilityRegistry->method('popAllUtilities')
        ->will($this->returnValue(['BatchJobHelper', 'ReportDownloader/file']));
    $this->adsHeaderFormatter = new AdsHeaderFormatter($adsUtilityRegistry);
  }

  /**
   * @covers Google\AdsApi\Common\AdsHeaderFormatter::formatApplicationNameForSoapHeader
   */
  public function testFormatApplicationNameForSoapHeader() {
    $libraryMetadataProviderMock = $this->getMock(
        LibraryMetadataProvider::class);
    $libraryMetadataProviderMock->expects($this->once())
        ->method('getLibName')
        ->will($this->returnValue('googleads-php-lib2'));
    $libraryMetadataProviderMock->expects($this->once())
        ->method('getLibVersion')
        ->will($this->returnValue('1.0.0-alpha'));

    $search = $this->applicationNames
        ->getRegexForFormattedApplicationName('Google report runner');
    $formattedApplicationName = $this->adsHeaderFormatter
        ->formatApplicationNameForSoapHeader(
            'Google report runner',
            'Dfp',
            $libraryMetadataProviderMock,
            false
        );
    $this->assertRegExp($search, $formattedApplicationName);
  }

  /**
   * @covers Google\AdsApi\Common\AdsHeaderFormatter::formatApplicationNameForSoapHeader
   */
  public function testFormatApplicationNameWithUtilitiesForSoapHeader() {
    $libraryMetadataProviderMock = $this->getMock(
        LibraryMetadataProvider::class);
    $libraryMetadataProviderMock->expects($this->once())
        ->method('getLibName')
        ->will($this->returnValue('googleads-php-lib2'));
    $libraryMetadataProviderMock->expects($this->once())
        ->method('getLibVersion')
        ->will($this->returnValue('1.0.0-alpha'));

    $search = $this->applicationNames
        ->getRegexForFormattedApplicationName('Google report runner');

    $formattedApplicationName = $this->adsHeaderFormatter
        ->formatApplicationNameForSoapHeader(
            'Google report runner',
            'Aw',
            $libraryMetadataProviderMock,
            true
        );
    $this->assertRegExp($search, $formattedApplicationName);
  }
}
