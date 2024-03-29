<?php
/**
 * Part of the Joomla Twitter Facebook Package
 *
 * @copyright  Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Twitter;

use Joomla\Http\Http;
use Joomla\Uri\Uri;

/**
 * Twitter API object class for the Joomla Framework.
 *
 * @since       __DEPLOY_VERSION__
 * @deprecated  The joomla/twitter package is deprecated
 */
abstract class AbstractTwitterObject
{
	/**
	 * @var    array  Options for the Twitter object.
	 * @since  __DEPLOY_VERSION__
	 */
	protected $options;

	/**
	 * @var    Http  The HTTP client object to use in sending HTTP requests.
	 * @since  __DEPLOY_VERSION__
	 */
	protected $client;

	/**
	 * @var   OAuth The OAuth client.
	 * @since __DEPLOY_VERSION__
	 */
	protected $oauth;

	/**
	 * Constructor.
	 *
	 * @param   array  $options  Twitter options array.
	 * @param   Http   $client   The HTTP client object.
	 * @param   OAuth  $oauth    The OAuth client.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function __construct(&$options, Http $client, OAuth $oauth)
	{
		$this->options = $options;
		$this->client = $client;
		$this->oauth = $oauth;
	}

	/**
	 * Method to check the rate limit for the requesting IP address
	 *
	 * @param   string  $resource  A resource or a comma-separated list of resource families you want to know the current rate limit disposition for.
	 * @param   string  $action    An action for the specified resource, if only one resource is specified.
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 * @throws  \RuntimeException
	 */
	public function checkRateLimit($resource = null, $action = null)
	{
		// Check the rate limit for remaining hits
		$rateLimit = $this->getRateLimit($resource);

		$property = '/' . $resource;

		if (!is_null($action))
		{
			$property .= '/' . $action;
		}

		if ($rateLimit->resources->$resource->$property->remaining == 0)
		{
			// The IP has exceeded the Twitter API rate limit
			throw new \RuntimeException(
				sprintf(
					'This server has exceed the Twitter API rate limit for the given period.  The limit will reset at %s',
					$rateLimit->resources->$resource->$property->reset
				)
			);
		}
	}

	/**
	 * Method to build and return a full request URL for the request.  This method will
	 * add appropriate pagination details if necessary and also prepend the API url
	 * to have a complete URL for the request.
	 *
	 * @param   string  $path        URL to inflect
	 * @param   array   $parameters  The parameters passed in the URL.
	 *
	 * @return  string  The request URL.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function fetchUrl($path, $parameters = null)
	{
		if ($parameters)
		{
			foreach ($parameters as $key => $value)
			{
				if (strpos($path, '?') === false)
				{
					$path .= '?' . $key . '=' . $value;
				}
				else
				{
					$path .= '&' . $key . '=' . $value;
				}
			}
		}

		// Get a new Uri object using the api url and given path.
		if (strpos($path, 'http://search.twitter.com/search.json') === false)
		{
			$apiUrl = isset($this->options['api.url']) ? $this->options['api.url'] : null;
			$uri = new Uri($apiUrl . $path);
		}
		else
		{
			$uri = new Uri($path);
		}

		return (string) $uri;
	}

	/**
	 * Method to retrieve the rate limit for the requesting IP address
	 *
	 * @param   string  $resource  A resource or a comma-separated list of resource families you want to know the current rate limit disposition for.
	 *
	 * @return  array  The JSON response decoded
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getRateLimit($resource)
	{
		// Build the request path.
		$path = '/application/rate_limit_status.json';

		if (!is_null($resource))
		{
			return $this->sendRequest($path, 'GET',  array('resources' => $resource));
		}

		return $this->sendRequest($path);
	}

	/**
	 * Method to send the request.
	 *
	 * @param   string  $path     The path of the request to make
	 * @param   string  $method   The request method.
	 * @param   mixed   $data     Either an associative array or a string to be sent with the post request.
	 * @param   array   $headers  An array of name-value pairs to include in the header of the request
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   __DEPLOY_VERSION__
	 * @throws  \RuntimeException
	 */
	public function sendRequest($path, $method = 'GET', $data = array(), $headers = array())
	{
		// Get the access token.
		$token = $this->oauth->getToken();

		// Set parameters.
		$parameters['oauth_token'] = $token['key'];

		// Send the request.
		$response = $this->oauth->oauthRequest($this->fetchUrl($path), $method, $parameters, $data, $headers);

		if (strpos($path, 'update_with_media') !== false)
		{
			// Check Media Rate Limit.
			$responseHeaders = $response->headers;

			if ($responseHeaders['x-mediaratelimit-remaining'] == 0)
			{
				// The IP has exceeded the Twitter API media rate limit
				throw new \RuntimeException(
					sprintf(
						'This server has exceed the Twitter API media rate limit for the given period.  The limit will reset in %s seconds.',
						$responseHeaders['x-mediaratelimit-reset']
					)
				);
			}
		}

		if (strpos($response->body, 'redirected') !== false)
		{
			return $response->headers['Location'];
		}

		return json_decode($response->body);
	}

	/**
	 * Get an option from the Twitter Object instance.
	 *
	 * @param   string  $key  The name of the option to get.
	 *
	 * @return  mixed  The option value.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getOption($key)
	{
		return isset($this->options[$key]) ? $this->options[$key] : null;
	}

	/**
	 * Set an option for the Twitter Object instance.
	 *
	 * @param   string  $key    The name of the option to set.
	 * @param   mixed   $value  The option value to set.
	 *
	 * @return  AbstractTwitterObject  This object for method chaining.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function setOption($key, $value)
	{
		$this->options[$key] = $value;

		return $this;
	}
}
