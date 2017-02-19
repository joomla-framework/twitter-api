<?php
/**
 * @copyright  Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Twitter\Tests;

use Joomla\Twitter\Followers;
use \DomainException;
use \RuntimeException;
use \stdClass;

require_once __DIR__ . '/case/TwitterTestCase.php';

/**
 * Test class for Twitter Followers.
 *
 * @since  1.0
 */
class FollowersTest extends TwitterTestCase
{
	/**
	 * @var    string  Sample JSON error message.
	 * @since  12.3
	 */
	protected $errorString = '{"error":"Generic error"}';

	/**
	 * @var    string  Sample JSON string.
	 * @since  1.0
	 */
	protected $followersRateLimit = '{"resources": {"followers": {
			"/followers/list": {"remaining":15, "reset":"Mon Jun 25 17:20:53 +0000 2012"},
			"/followers/ids": {"remaining":15, "reset":"Mon Jun 25 17:20:53 +0000 2012"}
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

		$this->object = new Followers($this->options, $this->client, $this->oauth);
	}

	/**
	 * Provides test data for request format detection.
	 *
	 * @return array
	 *
	 * @since 1.0
	 */
	public function seedUser()
	{
		// User ID or screen name
		return array(
			array(234654235457),
			array('testUser'),
			array(null)
			);
	}

	/**
	 * Tests the getFollowerIds method
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @dataProvider  seedUser
	 * @since   1.0
	 */
	public function testGetFollowers($user)
	{
		$cursor = 123;
		$count = 5;
		$skip_status = false;
		$entities = false;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->followersRateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "followers"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set request parameters.
		if (is_numeric($user))
		{
			$data['user_id'] = $user;
		}
		elseif (is_string($user))
		{
			$data['screen_name'] = $user;
		}
		else
		{
			// expectException was added in PHPUnit 5.2 and setExpectedException removed in 6.0
			if (method_exists($this, 'expectException'))
			{
				$this->expectException('RuntimeException');
			}
			else
			{
				$this->setExpectedException('RuntimeException');
			}

			$this->object->getFollowers($user, $cursor, $count, $skip_status, $entities);
		}

		$data['cursor'] = $cursor;
		$data['count'] = $count;
		$data['skip_status'] = $skip_status;
		$data['entities'] = $entities;

		$path = $this->object->fetchUrl('/followers/list.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getFollowers($user, $cursor, $count, $skip_status, $entities),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getFollowers method - failure
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @dataProvider  seedUser
	 * @since   1.0
	 * @expectedException  DomainException
	 */
	public function testGetFollowersFailure($user)
	{
		$cursor = 123;
		$count = 5;
		$skip_status = false;
		$entities = false;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->followersRateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "followers"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set request parameters.
		if (is_numeric($user))
		{
			$data['user_id'] = $user;
		}
		elseif (is_string($user))
		{
			$data['screen_name'] = $user;
		}
		else
		{
			// expectException was added in PHPUnit 5.2 and setExpectedException removed in 6.0
			if (method_exists($this, 'expectException'))
			{
				$this->expectException('RuntimeException');
			}
			else
			{
				$this->setExpectedException('RuntimeException');
			}

			$this->object->getFollowers($user, $cursor, $count, $skip_status, $entities);
		}

		$data['cursor'] = $cursor;
		$data['count'] = $count;
		$data['skip_status'] = $skip_status;
		$data['entities'] = $entities;

		$path = $this->object->fetchUrl('/followers/list.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getFollowers($user, $cursor, $count, $skip_status, $entities);
	}

	/**
	 * Tests the getFollowerIds method
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @dataProvider  seedUser
	 * @since   1.0
	 */
	public function testGetFollowerIds($user)
	{
		$cursor = 123;
		$string_ids = true;
		$count = 5;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->followersRateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "followers"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Set request parameters.
		if (is_numeric($user))
		{
			$data['user_id'] = $user;
		}
		elseif (is_string($user))
		{
			$data['screen_name'] = $user;
		}
		else
		{
			// expectException was added in PHPUnit 5.2 and setExpectedException removed in 6.0
			if (method_exists($this, 'expectException'))
			{
				$this->expectException('RuntimeException');
			}
			else
			{
				$this->setExpectedException('RuntimeException');
			}

			$this->object->getFollowerIds($user, $cursor, $string_ids, $count);
		}

		$data['cursor'] = $cursor;
		$data['stringify_ids'] = $string_ids;
		$data['count'] = $count;

		$path = $this->object->fetchUrl('/followers/ids.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getFollowerIds($user, $cursor, $string_ids, $count),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getFollowerIds method - failure
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @dataProvider  seedUser
	 * @since   1.0
	 * @expectedException  DomainException
	 */
	public function testGetFollowerIdsFailure($user)
	{
		$cursor = 123;
		$string_ids = true;
		$count = 5;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->followersRateLimit;

		$path = $this->object->fetchUrl('/application/rate_limit_status.json', array("resources" => "followers"));

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Set request parameters.
		if (is_numeric($user))
		{
			$data['user_id'] = $user;
		}
		elseif (is_string($user))
		{
			$data['screen_name'] = $user;
		}
		else
		{
			// expectException was added in PHPUnit 5.2 and setExpectedException removed in 6.0
			if (method_exists($this, 'expectException'))
			{
				$this->expectException('RuntimeException');
			}
			else
			{
				$this->setExpectedException('RuntimeException');
			}

			$this->object->getFollowerIds($user, $cursor, $string_ids, $count);
		}

		$data['cursor'] = $cursor;
		$data['stringify_ids'] = $string_ids;
		$data['count'] = $count;

		$path = $this->object->fetchUrl('/followers/ids.json', $data);

		$this->client->expects($this->at(1))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getFollowerIds($user, $cursor, $string_ids, $count);
	}
}
