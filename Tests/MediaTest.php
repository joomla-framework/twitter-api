<?php
/**
 * @copyright  Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Twitter\Tests;

use Joomla\Twitter\Media;
use \DomainException;
use \stdClass;

require_once __DIR__ . '/case/TwitterTestCase.php';

/**
 * Test class for Twitter Help.
 *
 * @since  1.0
 */
class MediaTest extends TwitterTestCase
{
	/**
	 * @var    string  Sample JSON error message.
	 * @since  12.3
	 */
	protected $errorString = '{"errors":[{"message":"Sorry, that page does not exist","code":34}]}';

	/**
	 * @var    string  Sample JSON string.
	 * @since  1.0
	 */
	protected $rateLimit = '{"resources": {"media": {
			"/media/upload": {"remaining":15, "reset":"Mon Jun 25 17:20:53 +0000 2012"}
			}}}';

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 *
	 * @return void
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->object = new Media($this->options, $this->client, $this->oauth);
	}

	/**
	 * Provides test data for request format detection.
	 *
	 * @return array
	 *
	 * @since 1.0
	 */
	public function seedUploads()
	{
		$testFile = __DIR__ . '/stubs/koala.jpg';
		$data = file_get_contents($testFile);

		return array(
			array($data, null, null),
			array($data, null, '234654235457'),
			array(null, base64_encode($data), null),
			array(null, base64_encode($data), '234654235457')
			);
	}

	/**
	 * Tests the upload method
	 *
	 * @param   string  $media_raw     Raw binary data to be uploaded.
	 * @param   string  $media_base64  A base64 encoded string containing the data to be uploaded.
	 *                                 This cannot be used in conjunction with $media_raw
	 * @param   string  $owners        A comma-separated string of user IDs to set as additional owners
	 *                                 who are allowed to use the returned media_id in Tweets or Cards. A maximum of
	 *                                 100 additional owners may be specified.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @dataProvider seedUploads
	 */
	public function testUpload($media_raw, $media_base64, $owners)
	{
		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "media"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Determine which type of data was passed for $media
		if (!is_null($media_raw))
		{
			$data['media'] = $media_raw;
		}
		elseif (!is_null($media_base64))
		{
			$data['media_data'] = $media_base64;
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->upload($media_raw, $media_base64, $owners);
		}

		if (!is_null($media_raw) && !is_null($media_base64))
		{
			$this->setExpectedException('RuntimeException');
			$this->object->upload($media_raw, $media_base64, $owners);
		}

		if (!is_null($owners))
		{
			$data['additional_owners'] = $owners;
		}

		$path = $this->object->fetchUrl('/media/upload.json');

		$this->client->expects($this->at(1))
		->method('post')
		->with($path, $data)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->upload($media_raw, $media_base64, $owners),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the upload method - failure
	 *
	 * @param   string  $media_raw     Raw binary data to be uploaded.
	 * @param   string  $media_base64  A base64 encoded string containing the data to be uploaded.
	 *                                 This cannot be used in conjunction with $media_raw
	 * @param   string  $owners        A comma-separated string of user IDs to set as additional owners
	 *                                 who are allowed to use the returned media_id in Tweets or Cards. A maximum of
	 *                                 100 additional owners may be specified.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @dataProvider seedUploads
	 * @expectedException DomainException
	 */
	public function testUploadFailure($media_raw, $media_base64, $owners)
	{
		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->rateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "media"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Determine which type of data was passed for $media
		if (!is_null($media_raw))
		{
			$data['media'] = $media_raw;
		}
		elseif (!is_null($media_base64))
		{
			$data['media_data'] = $media_base64;
		}
		else
		{
			$this->setExpectedException('RuntimeException');
			$this->object->upload($media_raw, $media_base64, $owners);
		}

		if (!is_null($media_raw) && !is_null($media_base64))
		{
			$this->setExpectedException('RuntimeException');
			$this->object->upload($media_raw, $media_base64, $owners);
		}

		if (!is_null($owners))
		{
			$data['additional_owners'] = $owners;
		}

		$path = $this->object->fetchUrl('/media/upload.json');

		$this->client->expects($this->at(1))
		->method('post')
		->with($path, $data)
		->will($this->returnValue($returnData));

		$this->object->upload($media_raw, $media_base64, $owners);
	}
}
