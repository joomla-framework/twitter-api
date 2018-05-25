<?php
/**
 * @copyright  Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Twitter\Tests;

use Joomla\Twitter\Mute;
use \DomainException;
use \stdClass;

/**
 * Test class for Twitter Mutes.
 *
 * @since  1.0
 */
class MuteTest extends TwitterTestCase
{
	/**
	 * @var    string  Sample JSON error message.
	 * @since  12.3
	 */
	protected $errorString = '{"error":"Generic error"}';

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

		$this->object = new Mute($this->options, $this->client, $this->oauth);
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
	 * Tests the mute method
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @since 1.0
	 * @dataProvider seedUser
	 */
	public function testMute($user)
	{
		$entities = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		// Determine which type of data was passed for $user
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

			$this->object->mute($user);
		}

		$this->client->expects($this->at(0))
		->method('post')
		->with('/mutes/users/create.json', $data)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->mute($user),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the mute method - failure
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  void
	 *
	 * @since 1.0
	 * @dataProvider seedUser
	 * @expectedException  DomainException
	 */
	public function testMuteFailure($user)
	{
		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		// Determine which type of data was passed for $user
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

			$this->object->mute($user);
		}

		$this->client->expects($this->at(0))
		->method('post')
		->with('/mutes/users/create.json', $data)
		->will($this->returnValue($returnData));

		$this->object->mute($user);
	}

	/**
	 * Tests the getMutedUserIds method
	 *
	 * @return  void
	 *
	 * @since 1.0
	 */
	public function testGetMutedUserIds()
	{
		$cursor = 5;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$data['cursor'] = $cursor;

		$path = $this->object->fetchUrl('/mutes/users/ids.json', $data);

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getMutedUserIds($cursor),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getMutedUserIds method - failure
	 *
	 * @return  void
	 *
	 * @since 1.0
	 * @expectedException  DomainException
	 */
	public function testGetMutedUserIdsFailure()
	{
		$cursor = 5;

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		$data['cursor'] = $cursor;

		$path = $this->object->fetchUrl('/mutes/users/ids.json', $data);

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getMutedUserIds($cursor);
	}

	/**
	 * Tests the getMutedUsers method
	 *
	 * @return  void
	 *
	 * @since 1.0
	 */
	public function testGetMutedUsers()
	{
		$cursor = 5;
		$entities = true;
		$skip_status = true;

		$returnData = new stdClass;
		$returnData->code = 200;
		$returnData->body = $this->sampleString;

		$data['cursor'] = $cursor;
		$data['include_entities'] = $entities;
		$data['skip_status'] = $skip_status;

		$path = $this->object->fetchUrl('/mutes/users/list.json', $data);

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->assertThat(
			$this->object->getMutedUsers($cursor, $entities, $skip_status),
			$this->equalTo(json_decode($this->sampleString))
		);
	}

	/**
	 * Tests the getMutedUsers method - failure
	 *
	 * @return  void
	 *
	 * @since 1.0
	 * @expectedException  DomainException
	 */
	public function testGetMutedUsersFailure()
	{
		$cursor = 5;
		$entities = true;
		$skip_status = true;

		$returnData = new stdClass;
		$returnData->code = 500;
		$returnData->body = $this->errorString;

		$data['cursor'] = $cursor;
		$data['include_entities'] = $entities;
		$data['skip_status'] = $skip_status;

		$path = $this->object->fetchUrl('/mutes/users/list.json', $data);

		$this->client->expects($this->at(0))
		->method('get')
		->with($path)
		->will($this->returnValue($returnData));

		$this->object->getMutedUsers($cursor, $entities, $skip_status);
	}
}
