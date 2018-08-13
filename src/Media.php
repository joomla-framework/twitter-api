<?php
/**
 * Part of the Joomla Framework Twitter Package
 *
 * @copyright  Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Twitter;

/**
 * Twitter API Media class for the Joomla Framework.
 *
 * @since       1.2.0
 * @deprecated  The joomla/twitter package is deprecated
 */
class Media extends Object
{
	/**
	 * Method to upload media.
	 *
	 * @param   string  $rawMedia     Raw binary data to be uploaded.
	 * @param   string  $base64Media  A base64 encoded string containing the data to be uploaded.
	 *                                This cannot be used in conjunction with $rawMedia
	 * @param   string  $owners       A comma-separated string of user IDs to set as additional owners who are allowed to use the returned media_id
	 *                                in Tweets or Cards. A maximum of 100 additional owners may be specified.
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   1.2.0
	 * @throws  \RuntimeException
	 */
	public function upload($rawMedia = null, $base64Media = null, $owners = null)
	{
		// Check the rate limit for remaining hits
		$this->checkRateLimit('media', 'upload');

		// Determine which type of data was passed for $media
		if ($rawMedia !== null)
		{
			$data['media'] = $rawMedia;
		}
		elseif ($base64Media !== null)
		{
			$data['media_data'] = $base64Media;
		}
		else
		{
			// We don't have a valid entry
			throw new \RuntimeException('You must specify at least one valid media type.');
		}

		if ($rawMedia !== null && $base64Media !== null)
		{
			throw new \RuntimeException('You may only specify one type of media.');
		}

		if ($owners !== null)
		{
			$data['additional_owners'] = $owners;
		}

		// Set the API path
		$path = '/media/upload.json';

		// Send the request.
		return $this->sendRequest($path, 'POST', $data);
	}
}
