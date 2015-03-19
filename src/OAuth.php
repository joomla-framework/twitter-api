<?php
/**
 * Part of the Joomla Framework Twitter Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Twitter;

use Joomla\OAuth1\Client;
use Joomla\Http\Http;
use Joomla\Input\Input;
use Joomla\Application\AbstractWebApplication;

/**
 * Joomla Framework class for generating Twitter API access token.
 *
 * @since  1.0
 */
class OAuth extends Client
{
	/**
	 * Constructor.
	 *
	 * @param   AbstractWebApplication  $application  The application object
	 * @param   Http                    $client       The HTTP client object.
	 * @param   Input                   $input        The input object
	 * @param   array                   $options      OAuth1 Client options array.
	 *
	 * @since   1.0
	 */
	public function __construct(AbstractWebApplication $application, Http $client = null, Input $input = null, $options = array())
	{
		// Call the OAuth1 Client constructor to setup the object.
		parent::__construct($application, $client, $input, $options);

		// Define some defaults
		if ($this->getOption('accessTokenURL') === null)
		{
			$this->setOption('accessTokenURL', 'https://api.twitter.com/oauth/access_token');
		}

		if ($this->getOption('authenticateURL') === null)
		{
			$this->setOption('authenticateURL', 'https://api.twitter.com/oauth/authenticate');
		}

		if ($this->getOption('authoriseURL') === null)
		{
			$this->setOption('authoriseURL', 'https://api.twitter.com/oauth/authorize');
		}

		if ($this->getOption('requestTokenURL') === null)
		{
			$this->setOption('requestTokenURL', 'https://api.twitter.com/oauth/request_token');
		}
	}

	/**
	 * Method to verify if the access token is valid by making a request.
	 *
	 * @return  boolean  Returns true if the access token is valid and false otherwise.
	 *
	 * @since   1.0
	 */
	public function verifyCredentials()
	{
		$token = $this->getToken();

		// Set the parameters.
		$parameters = array('oauth_token' => $token['key']);

		// Set the API base
		$path = 'https://api.twitter.com/1.1/account/verify_credentials.json';

		// Send the request.
		$response = $this->oauthRequest($path, 'GET', $parameters);

		// Verify response
		return $response->code == 200;
	}

	/**
	 * Ends the session of the authenticating user, returning a null cookie.
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   1.0
	 */
	public function endSession()
	{
		$token = $this->getToken();

		// Set parameters.
		$parameters = array('oauth_token' => $token['key']);

		// Set the API base
		$path = 'https://api.twitter.com/1.1/account/end_session.json';

		// Send the request.
		$response = $this->oauthRequest($path, 'POST', $parameters);

		return json_decode($response->body);
	}

	/**
	 * Method to validate a response.
	 *
	 * @param   string                 $url       The request URL.
	 * @param   \Joomla\Http\Response  $response  The response to validate.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @throws  \DomainException
	 */
	public function validateResponse($url, $response)
	{
		if (strpos($url, 'verify_credentials') === false && $response->code != 200)
		{
			$error = json_decode($response->body);

			if (property_exists($error, 'error'))
			{
				throw new \DomainException($error->error);
			}

			$error = $error->errors;

			throw new \DomainException($error[0]->message, $error[0]->code);
		}
	}
}
