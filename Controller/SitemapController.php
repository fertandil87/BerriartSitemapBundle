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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Berriart\Bundle\SitemapBundle\Manager\Sitemap;

class SitemapController extends ContainerAware {
    private $sitemap;
    private $request;
    private $templating;

    public function __construct(Sitemap $sitemap, EngineInterface $templating, Request $request)
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

    public function sitemapGzip()
    {
        $page = $this->getRequest()->get('page', 1);

        $this->sitemap->setPage($page);

        $data = $this->templating->render('BerriartSitemapBundle:Sitemap:sitemap.xml.twig', array(
            'sitemap' => $this->sitemap
        ));

        $data = gzencode($data, 9);

        $response = new Response($data, 200, array(
            'Content-Disposition' => 'attachment; filename="' . basename($this->getSitemapUrl($page)))
        );

        return $response;
    }

    public function sitemapIndex()
    {
        $numberOfPages = $this->sitemap->pages();

        $urlPattern = $this->getUrlPattern();
        $gzip = $this->container->getParameter('berriart_sitemap.config.gzip');

        return $this->templating->renderResponse('BerriartSitemapBundle:Sitemap:sitemapindex.xml.twig', array(
            'pages' => $numberOfPages,
            'dump' => $gzip,
            'url' => $urlPattern,
            'lastmod' => new \DateTime()
        ));
    }

    private function getSitemapUrl($page = 1)
    {
        $urlPattern = $this->getUrlPattern();

        return sprintf($urlPattern, $page);
    }

    private function getUrlPattern()
    {
        $urlPattern = '/sitemap.%d.xml';
        $gzip = $this->container->getParameter('berriart_sitemap.config.dump_gzip');

        if ($gzip && !preg_match('/(.*)\.gz/', $urlPattern)) {
            $urlPattern = $urlPattern . '.gz';
        }

        return $urlPattern;
    }
}
