<?php

namespace Berriart\Bundle\SitemapBundle\Entity;

/**
 * Video data used for video sitemaps.
 *
 * (c) John Michael Luy <johnmichael.luy@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * Berriart\Bundle\SitemapBundle\Entity\Video
 */
class Video
{
    /**
     * @var integer $id
     */
    protected $id;

    /**
     * @var string $content_loc
     */
    protected $content_loc;

    /**
     * @var string $description
     */
    protected $description;

    /**
     * @var integer $duration
     */
    protected $duration;

    /**
     * @var \DateTime $publication_date
     */
    protected $publication_date;

    /**
     * @var string $thumbnail_loc
     */
    protected $thumbnail_loc;

    /**
     * @var string $title
     */
    protected $title;

    /**
     * @var \Berriart\Bundle\SitemapBundle\Entity\Url $url
     */
    protected $url;

    /**
     * @param string $content_loc
     */
    public function setContentLoc($content_loc) {
        $this->content_loc = $content_loc;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getContentLoc() {
        return $this->content_loc;
    }

    /**
     * @param string $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param int $duration
     */
    public function setDuration($duration) {
        $this->duration = $duration;
    }

    /**
     * @return int
     */
    public function getDuration() {
        return $this->duration;
    }

    /**
     * @param \DateTime $publication_date
     */
    public function setPublicationDate($publication_date) {
        $this->publication_date = $publication_date;
    }

    /**
     * @return \DateTime
     */
    public function getPublicationDate() {
        return $this->publication_date;
    }


    /**
     * @param string $thumbnail_loc
     */
    public function setThumbnailLoc($thumbnail_loc) {
        $this->thumbnail_loc = $thumbnail_loc;
    }

    /**
     * @return string
     */
    public function getThumbnailLoc() {
        return $this->thumbnail_loc;
    }

    /**
     * @param string $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param \Berriart\Bundle\SitemapBundle\Entity\Url $url
     */
    public function setUrl($url) {
        $this->url = $url;
    }

    /**
     * @return \Berriart\Bundle\SitemapBundle\Entity\Url
     */
    public function getUrl() {
        return $this->url;
    }


}