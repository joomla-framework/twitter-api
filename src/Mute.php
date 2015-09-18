<?php
/**
 * Part of the Joomla Framework Twitter Package
 *
 * @copyright  Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Twitter;

/**
 * Twitter API Mute class for the Joomla Framework.
 *
 * @since  1.0
 */
class Mute extends Object
{
	/**
	 * Method to mute a user.
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function mute($user)
	{
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
		$path = '/mutes/users/create.json';

		// Send the request.
		return $this->sendRequest($path, 'POST', $data);
	}

	/**
	 * Method to un-mute a user.
	 *
	 * @param   mixed  $user  Either an integer containing the user ID or a string containing the screen name.
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function unmute($user)
	{
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
		$path = '/mutes/users/destroy.json';

		// Send the request.
		return $this->sendRequest($path, 'POST', $data);
	}

	/**
	 * Method to get a list of muted user ID's
	 *
	 * @param   integer  $cursor  Breaks the results into pages. A single page contains 20 lists. Provide a value of -1 to begin paging.
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   1.0
	 */
	public function getMutedUserIds($cursor = null)
	{
		// Check if cursor is specified
		if (!is_null($cursor))
		{
			$data['cursor'] = $cursor;
		}

		// Set the API path
		$path = '/mutes/users/ids.json';

		// Send the request.
		return $this->sendRequest($path, 'GET', $data);
	}

	/**
	 * Method to get a list of muted users
	 *
	 * @param   integer  $cursor       Breaks the results into pages. A single page contains 20 lists. Provide a value of -1 to begin paging.
	 * @param   boolean  $entities     When set to either true, t or 1, each user will include a node called "entities". This node offers a variety
	 * 								                 of metadata about the user in a discreet structure.
	 * @param   boolean  $skip_status  When set to either true, t or 1 statuses will not be included in the returned user objects.
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   1.0
	 */
	public function getMutedUsers($cursor = null, $entities = null, $skip_status = null)
	{
		// Check if cursor is specified
		if (!is_null($cursor))
		{
			$data['cursor'] = $cursor;
		}

		// Check if entities is specified
		if (!is_null($entities))
		{
			$data['include_entities'] = $entities;
		}

		// Check if skip_status is specified
		if (!is_null($skip_status))
		{
			$data['skip_status'] = $skip_status;
		}

		// Set the API path
		$path = '/mutes/users/list.json';

		// Send the request.
		return $this->sendRequest($path, 'GET', $data);
	}
}
