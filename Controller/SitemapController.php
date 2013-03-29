<?php

namespace Berriart\Bundle\SitemapBundle\Controller;

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

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Berriart\Bundle\SitemapBundle\Manager\Sitemap;

class SitemapController extends ContainerAware
{ 
    private $sitemap;
    private $request;
    private $templating;

    public function __construct(Sitemap $sitemap, EngineInterface $templating, Request $request) //, EngineInterface $templating)
    {
        $this->sitemap = $sitemap;
        $this->request = $request;
        $this->templating = $templating;
    }
    
    public function getRequest()
    {
        return $this->request;
    }
    
    public function sitemap()
    {
        $page = $this->getRequest()->get('page', 1);

        $this->sitemap->setPage($page);

        return $this->templating->renderResponse('BerriartSitemapBundle:Sitemap:sitemap.xml.twig', array(
            'sitemap' => $this->sitemap
        ));
    }
    
    public function sitemapIndex()
    {
        $urlPattern =  $this->container->getParameter('berriart_sitemap.config.gzip_url') .
            $this->container->getParameter('berriart_sitemap.config.gzip_file_pattern');
        return $this->templating->renderResponse('BerriartSitemapBundle:Sitemap:sitemapindex.xml.twig', array(
            'pages' => $this->sitemap->pages(),
            'gzip' => $this->container->getParameter('berriart_sitemap.config.gzip'),
            'url' => $urlPattern
        ));
    }
}
