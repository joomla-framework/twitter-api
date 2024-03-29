<?php
/**
 * Part of the Joomla Framework Twitter Package
 *
 * @copyright  Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Twitter;

/**
 * Twitter API Direct Messages class for the Joomla Framework.
 *
 * @since       1.0
 * @deprecated  The joomla/twitter package is deprecated
 */
class Directmessages extends AbstractTwitterObject
{
	/**
	 * Method to get the most recent direct messages sent to the authenticating user.
	 *
	 * @param   integer  $sinceId     Returns results with an ID greater than (that is, more recent than) the specified ID.
	 * @param   integer  $maxId       Returns results with an ID less than (that is, older than) or equal to the specified ID.
	 * @param   integer  $count       Specifies the number of direct messages to try and retrieve, up to a maximum of 200.
	 * @param   boolean  $entities    When set to true,  each tweet will include a node called "entities,". This node offers a variety of metadata
	 *                                about the tweet in a discreet structure, including: user_mentions, urls, and hashtags.
	 * @param   boolean  $skipStatus  When set to either true, t or 1 statuses will not be included in the returned user objects.
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   1.0
	 */
	public function getDirectMessages($sinceId = 0, $maxId =  0, $count = 20, $entities = null, $skipStatus = null)
	{
		// Check the rate limit for remaining hits
		$this->checkRateLimit('direct_messages');

		// Set the API path
		$path = '/direct_messages.json';

		$data = array();

		// Check if since_id is specified.
		if ($sinceId)
		{
			$data['since_id'] = $sinceId;
		}

		// Check if max_id is specified.
		if ($maxId)
		{
			$data['max_id'] = $maxId;
		}

		// Check if count is specified.
		if ($count)
		{
			$data['count'] = $count;
		}

		// Check if entities is specified.
		if (!is_null($entities))
		{
			$data['include_entities'] = $entities;
		}

		// Check if skip_status is specified.
		if (!is_null($skipStatus))
		{
			$data['skip_status'] = $skipStatus;
		}

		// Send the request.
		return $this->sendRequest($path, 'GET', $data);
	}

	/**
	 * Method to get the most recent direct messages sent by the authenticating user.
	 *
	 * @param   integer  $sinceId   Returns results with an ID greater than (that is, more recent than) the specified ID.
	 * @param   integer  $maxId     Returns results with an ID less than (that is, older than) or equal to the specified ID.
	 * @param   integer  $count     Specifies the number of direct messages to try and retrieve, up to a maximum of 200.
	 * @param   integer  $page      Specifies the page of results to retrieve.
	 * @param   boolean  $entities  When set to true,  each tweet will include a node called "entities,". This node offers a variety of metadata
	 *                              about the tweet in a discreet structure, including: user_mentions, urls, and hashtags.
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   1.0
	 */
	public function getSentDirectMessages($sinceId = 0, $maxId =  0, $count = 20, $page = 0, $entities = null)
	{
		// Check the rate limit for remaining hits
		$this->checkRateLimit('direct_messages', 'sent');

		// Set the API path
		$path = '/direct_messages/sent.json';

		$data = array();

		// Check if since_id is specified.
		if ($sinceId)
		{
			$data['since_id'] = $sinceId;
		}

		// Check if max_id is specified.
		if ($maxId)
		{
			$data['max_id'] = $maxId;
		}

		// Check if count is specified.
		if ($count)
		{
			$data['count'] = $count;
		}

		// Check if page is specified.
		if ($page)
		{
			$data['page'] = $page;
		}

		// Check if entities is specified.
		if (!is_null($entities))
		{
			$data['include_entities'] = $entities;
		}

		// Send the request.
		return $this->sendRequest($path, 'GET', $data);
	}

	/**
	 * Method to send a new direct message to the specified user from the authenticating user.
	 *
	 * @param   mixed   $user  Either an integer containing the user ID or a string containing the screen name.
	 * @param   string  $text  The text of your direct message. Be sure to keep the message under 140 characters.
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function sendDirectMessages($user, $text)
	{
		// Set the API path
		$path = '/direct_messages/new.json';

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

		$data['text'] = $text;

		// Send the request.
		return $this->sendRequest($path, 'POST', $data);
	}

	/**
	 * Method to get a single direct message, specified by an id parameter.
	 *
	 * @param   integer  $id  The ID of the direct message.
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   1.0
	 */
	public function getDirectMessagesById($id)
	{
		// Check the rate limit for remaining hits
		$this->checkRateLimit('direct_messages', 'show');

		// Set the API path
		$path = '/direct_messages/show.json';

		$data['id'] = $id;

		// Send the request.
		return $this->sendRequest($path, 'GET', $data);
	}

	/**
	 * Method to delete the direct message specified in the required ID parameter.
	 *
	 * @param   integer  $id        The ID of the direct message.
	 * @param   boolean  $entities  When set to true,  each tweet will include a node called "entities,". This node offers a variety of metadata
	 *                              about the tweet in a discreet structure, including: user_mentions, urls, and hashtags.
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   1.0
	 */
	public function deleteDirectMessages($id, $entities = null)
	{
		// Set the API path
		$path = '/direct_messages/destroy.json';

		$data['id'] = $id;

		// Check if entities is specified.
		if (!is_null($entities))
		{
			$data['include_entities'] = $entities;
		}

		// Send the request.
		return $this->sendRequest($path, 'POST', $data);
	}
}
