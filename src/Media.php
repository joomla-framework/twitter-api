<?php
/**
 * Part of the Joomla Framework Twitter Package
 *
 * @copyright  Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Twitter;

/**
 * Twitter API Media class for the Joomla Framework.
 *
 * @since  __DEPLOY_VERSION__
 */
class Media extends Object
{
	/**
	 * Method to upload media.
	 *
	 * @param   string  $media_raw     Raw binary data to be uploaded.
	 * @param   string  $media_base64  A base64 encoded string containing the data to be uploaded.
	 *                                 This cannot be used in conjunction with $media_raw
	 * @param   string  $owners        A comma-separated string of user IDs to set as additional owners
	 *                                 who are allowed to use the returned media_id in Tweets or Cards. A maximum of
	 *                                 100 additional owners may be specified.
	 *
	 * @return  array  The decoded JSON response
	 *
	 * @since   __DEPLOY_VERSION__
	 * @throws  \RuntimeException
	 */
	public function upload($media_raw = null, $media_base64 = null, $owners = null)
	{
		// Check the rate limit for remaining hits
		$this->checkRateLimit('media', 'upload');

		// Determine which type of data was passed for $media
		if (!is_null($media_raw))
		{
			$data['media'] = $media_raw;
		}
		elseif (!is_null($media_base64))
		{
			$data['media_data'] = $media_base64;
		}
		else
		{
			// We don't have a valid entry
			throw new \RuntimeException('You must specify at least one valid media type.');
		}

		if (!is_null($media_raw) && !is_null($media_base64))
		{
			throw new \RuntimeException('You may only specify one type of media.');
		}

		if (!is_null($owners))
		{
			$data['additional_owners'] = $owners;
		}

		// Set the API path
		$path = '/media/upload.json';

		// Send the request.
		return $this->sendRequest($path, 'POST', $data);
	}
}
