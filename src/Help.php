<?php
/**
 * Part of the Joomla Framework Twitter Package
 *
 * @copyright  Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Twitter;

/**
 * Twitter API Help class for the Joomla Framework.
 *
 * @since       1.0
 * @deprecated  The joomla/twitter package is deprecated
 */
class Help extends AbstractTwitterObject
{
	/**
	 * Method to get the supported languages from the API.
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   1.0
	 */
	public function getLanguages()
	{
		// Check the rate limit for remaining hits
		$this->checkRateLimit('help', 'languages');

		// Set the API path
		$path = '/help/languages.json';

		// Send the request.
		return $this->sendRequest($path);
	}

	/**
	 * Method to get the current configuration used by Twitter including twitter.com slugs which are not usernames,
	 * maximum photo resolutions, and t.co URL lengths.
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   1.0
	 */
	public function getConfiguration()
	{
		// Check the rate limit for remaining hits
		$this->checkRateLimit('help', 'configuration');

		// Set the API path
		$path = '/help/configuration.json';

		// Send the request.
		return $this->sendRequest($path);
	}

	/**
	 * Method to get Twitter's Privacy Policy
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   1.2.0
	 */
	public function getPrivacy()
	{
		// Check the rate limit for remaining hits
		$this->checkRateLimit('help', 'privacy');

		// Set the API path
		$path = '/help/privacy.json';

		// Send the request
		return $this->sendRequest($path);
	}

	/**
	 * Method to get Twitter's Terms of Service
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   1.2.0
	 */
	public function getTos()
	{
		// Check the rate limit for remaining hits
		$this->checkRateLimit('help', 'tos');

		// Set the API path
		$path = '/help/tos.json';

		// Send the request
		return $this->sendRequest($path);
	}
}
