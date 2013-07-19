<?php

/**
 * @author Marcelo Silva <marcelosilva.developer@gmail.com>
 */

namespace Application;
use \InvalidArgumentException as Argument;

class Youtube extends AbstractYoutube
{
	/**
	 * @var string
	 */
	private $username;

	/**
	 * @var array
	 */
	private $videos = array();

	/**
	 * @var array
	 */
	private $params = array();

	/**
	 * @var int
	 */
	private $limit = 4;

	public function __construct($username)
	{
		if (!is_string($username)) {
			throw new Argument($username." user isn't a string");
		}

		$this->username = $username;
		$this->url = sprintf('http://gdata.youtube.com/feeds/api/users/%s/uploads', $this->username);
	}

	/**
	 * @see Application\Youtube::limit($limit)
	 * @param int $limit
	 * @return the own object
	 */
	public function limit($limit)
	{
		if (!is_int($limit)) {
			throw new Argument($limit.' is not an int number'); 
		}

		$this->limit = $limit;
		return $this;
	}

	/**
	 * @see Application\Youtube::accessVideos()
	 * @return an object XML with the videos
	 */
	protected function accessVideos()
	{	
		if ($this->limit) {
			$this->url .= '?max-results=' . $this->limit;
		}

		$result = $this->accessServer($this->url);
		$obj = new \SimpleXMLElement($result);

		if (!$obj) {
			throw new \Exception("Couldn't return the object");
		}

		return $obj;
	}

	/**
	 * @see Application\Youtube::getVideos()
	 * @return array $videos
	 */
	public function getVideos()
	{
		$xml = $this->accessVideos();
		foreach($xml->entry as $obj) {
			$url = (string)$obj->link['href'];
	
			parse_str(parse_url($url, PHP_URL_QUERY), $params);
			$id = $params['v'];

			$this->videos[] = array(
				'id' => (string)$id,
				'title' => (string)$obj->title,
				'description' => (string)$obj->content,
				'thumbnail' => 'http://i'. rand(1, 4) .'.ytimg.com/vi/'. $id .'/.hqdefault.jpg',
				'url' => $url
			);
		}

		return $this->videos;
	}
}