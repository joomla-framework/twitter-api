<?php
/**
 * Part of the Joomla Framework Twitter Package
 *
 * @copyright  Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Twitter;

/**
 * Twitter API Block class for the Joomla Framework.
 *
 * @since       1.0
 * @deprecated  The joomla/twitter package is deprecated
 */
class Block extends AbstractTwitterObject
{
	/**
	 * Method to get the user ids the authenticating user is blocking.
	 *
	 * @param   boolean  $stringifyIds  Provide this option to have ids returned as strings instead.
	 * @param   integer  $cursor        Causes the list of IDs to be broken into pages of no more than 5000 IDs at a time. The number of IDs returned
	 * 									is not guaranteed to be 5000 as suspended users are filtered out after connections are queried. If no cursor
	 * 									is provided, a value of -1 will be assumed, which is the first "page."
	 * @param   boolean  $full          Causes the list returned to contain full details of all blocks, instead of just the IDs.
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   1.0
	 */
	public function getBlocking($stringifyIds = null, $cursor = null, $full = null)
	{
		// Check the rate limit for remaining hits
		if (!is_null($full))
		{
			$this->checkRateLimit('blocks', 'ids');
		}
		else
		{
			$this->checkRateLimit('blocks', 'list');
		}

		$data = array();

		// Check if stringify_ids is specified
		if (!is_null($stringifyIds))
		{
			$data['stringify_ids'] = $stringifyIds;
		}

		// Check if cursor is specified
		if (!is_null($stringifyIds))
		{
			$data['cursor'] = $cursor;
		}

		// Set the API path
		$path = (is_null($full)) ? '/blocks/ids.json' : '/blocks/list.json';

		// Send the request.
		return $this->sendRequest($path, 'GET', $data);
	}

	/**
	 * Method to block the specified user from following the authenticating user.
	 *
	 * @param   mixed    $user        Either an integer containing the user ID or a string containing the screen name.
	 * @param   boolean  $entities    When set to either true, t or 1, each tweet will include a node called "entities,". This node offers a
	 * 								  variety of metadata about the tweet in a discreet structure, including: user_mentions, urls, and hashtags.
	 * @param   boolean  $skipStatus  When set to either true, t or 1 statuses will not be included in the returned user objects.
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function blockUser($user, $entities = null, $skipStatus = null)
	{
		// Check the rate limit for remaining hits
		$this->checkRateLimit('blocks', 'create');

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

		// Check if entities is specified
		if (!is_null($entities))
		{
			$data['include_entities'] = $entities;
		}

		// Check if skip_statuses is specified
		if (!is_null($skipStatus))
		{
			$data['skip_status'] = $skipStatus;
		}

		// Set the API path
		$path = '/blocks/create.json';

		// Send the request.
		return $this->sendRequest($path, 'POST', $data);
	}

	/**
	 * Method to unblock the specified user from following the authenticating user.
	 *
	 * @param   mixed    $user        Either an integer containing the user ID or a string containing the screen name.
	 * @param   boolean  $entities    When set to either true, t or 1, each tweet will include a node called "entities,". This node offers a
	 * 								  variety of metadata about the tweet in a discreet structure, including: user_mentions, urls, and hashtags.
	 * @param   boolean  $skipStatus  When set to either true, t or 1 statuses will not be included in the returned user objects.
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function unblock($user, $entities = null, $skipStatus = null)
	{
		// Check the rate limit for remaining hits
		$this->checkRateLimit('blocks', 'destroy');

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

		// Check if entities is specified
		if (!is_null($entities))
		{
			$data['include_entities'] = $entities;
		}

		// Check if skip_statuses is specified
		if (!is_null($skipStatus))
		{
			$data['skip_status'] = $skipStatus;
		}

		// Set the API path
		$path = '/blocks/destroy.json';

		// Send the request.
		return $this->sendRequest($path, 'POST', $data);
	}
}
