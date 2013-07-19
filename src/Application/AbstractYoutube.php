<?php

/**
 * @author Marcelo Silva <marcelosilva.developer@gmail.com>
 */

namespace Application;

abstract class AbstractYoutube
{
	/**
	 * @var string
	 */
	protected $url;

	/**
	 * @see Application\Youtube::accessServer()
	 * @param string $url
	 * @return $result
	 */
	protected function accessServer($url)
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);

		if (!$result) {
			throw new \Exception('Error in the server');
		}

		return $result;
	}

	/**
	 * @see Application\Youtube::accessVideos()
	 */
	abstract protected function accessVideos();

	/**
	 * @see Application\Youtube::getVideos()
	 * @return array $videos
	 */
	abstract protected function getVideos();
}