<?php

namespace Berriart\Bundle\SitemapBundle\Repository;

/**
 * This file is part of the BerriartSitemapBundle package what is based on the
 * AvalancheSitemapBundle
 *
 * (c) Bulat Shakirzyanov <avalanche123.com>
 * (c) Alberto Varela <alberto@berriart.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;
use Berriart\Bundle\SitemapBundle\Entity\Url;
use Berriart\Bundle\SitemapBundle\Repository\UrlRepositoryInterface;

/**
 * UrlRepository
 */
class UrlRepository extends EntityRepository implements UrlRepositoryInterface
{
    public function add(Url $url)
    {
        $em = $this->getEntityManager();
        $em->persist($url);
    }

    public function findAllOnPage($page, $limit = self::LIMIT)
    {
        $em = $this->getEntityManager();
        $maxResults = $limit;
        $firstResult = $maxResults * ($page - 1);
        $results = $em->createQuery('SELECT u FROM BerriartSitemapBundle:Url u ORDER BY u.id ASC')
            ->setFirstResult($firstResult)
            ->setMaxResults($maxResults)
            ->getResult();
        
        return $results;
    }

    public function findOneByLoc($loc)
    {
        return $this->findOneBy(array('loc' => $loc));
    }

    public function remove(Url $url)
    {
        $em = $this->getEntityManager();
        $em->remove($url);
    }

    public function pages($limit = self::LIMIT)
    {
        return max(ceil($this->countAll() / $limit), 1);
    }

    public function flush()
    {
        $em = $this->getEntityManager();
        $em->flush();
        $em->clear();
    }

    private function countAll()
    {
        $em = $this->getEntityManager();
        $results = $em->createQuery('SELECT COUNT(u) FROM BerriartSitemapBundle:Url u')
            ->getSingleResult();

        return $results[1];
    }
}