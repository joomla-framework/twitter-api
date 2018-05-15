<?php
/**
 * Part of the Joomla Framework Twitter Package
 *
 * @copyright  Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Twitter;

/**
 * Twitter API Followers class for the Joomla Framework.
 *
 * @since       1.2.0
 * @deprecated  The joomla/twitter package is deprecated
 */
class Followers extends Object
{
	/**
	 * Method to get an array of users the specified user is followed by.
	 *
	 * @param   mixed    $user        Either an integer containing the user ID or a string containing the screen name.
	 * @param   integer  $cursor      Causes the list of IDs to be broken into pages of no more than 5000 IDs at a time. The number of IDs returned
	 *                                is not guaranteed to be 5000 as suspended users are filtered out after connections are queried. If no cursor
	 *                                is provided, a value of -1 will be assumed, which is the first "page."
	 * @param   integer  $count       Specifies the number of IDs attempt retrieval of, up to a maximum of 5,000 per distinct request.
	 * @param   boolean  $skipStatus  When set to either true, t or 1, statuses will not be included in returned user objects.
	 * @param   boolean  $entities    When set to either true, t or 1, each user will include a node called "entities,". This node offers a
	 *                                variety of metadata about the user in a discrete structure.
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   1.2.0
	 * @throws  \RuntimeException
	 */
	public function getFollowers($user, $cursor = null, $count = 0, $skipStatus = null, $entities = null)
	{
		// Check the rate limit for remaining hits
		$this->checkRateLimit('followers', 'ids');

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
			// We don't have a valid entry
			throw new \RuntimeException('The specified username is not in the correct format; must use integer or string');
		}

		// Set the API path
		$path = '/followers/list.json';

		// Check if cursor is specified
		if (!is_null($cursor))
		{
			$data['cursor'] = $cursor;
		}

		// Check if count is specified
		if (!is_null($count))
		{
			$data['count'] = $count;
		}

		// Check if skip_status is specified
		if (!is_null($skipStatus))
		{
			$data['skip_status'] = $skipStatus;
		}

		// Check if entities is specified
		if (!is_null($entities))
		{
			$data['entities'] = $entities;
		}

		// Send the request.
		return $this->sendRequest($path, 'GET', $data);
	}

	/**
	 * Method to get an array of user IDs the specified user is followed by.
	 *
	 * @param   mixed    $user       Either an integer containing the user ID or a string containing the screen name.
	 * @param   integer  $cursor     Causes the list of IDs to be broken into pages of no more than 5000 IDs at a time. The number of IDs returned
	 *                               is not guaranteed to be 5000 as suspended users are filtered out after connections are queried. If no cursor
	 *                               is provided, a value of -1 will be assumed, which is the first "page."
	 * @param   boolean  $stringIds  Set to true to return IDs as strings, false to return as integers.
	 * @param   integer  $count      Specifies the number of IDs attempt retrieval of, up to a maximum of 5,000 per distinct request.
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   1.2.0
	 * @throws  \RuntimeException
	 */
	public function getFollowerIds($user, $cursor = null, $stringIds = null, $count = 0)
	{
		// Check the rate limit for remaining hits
		$this->checkRateLimit('followers', 'ids');

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
			// We don't have a valid entry
			throw new \RuntimeException('The specified username is not in the correct format; must use integer or string');
		}

		// Set the API path
		$path = '/followers/ids.json';

		// Check if cursor is specified
		if (!is_null($cursor))
		{
			$data['cursor'] = $cursor;
		}

		// Check if string_ids is specified
		if (!is_null($stringIds))
		{
			$data['stringify_ids'] = $stringIds;
		}

		// Check if count is specified
		if (!is_null($count))
		{
			$data['count'] = $count;
		}

		// Send the request.
		return $this->sendRequest($path, 'GET', $data);
	}
}
