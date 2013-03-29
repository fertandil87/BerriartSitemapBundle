<?php

namespace Berriart\Bundle\SitemapBundle\Command;

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

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class PopulateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('berriart:sitemap:populate')
            ->setDescription('Populate url database, using url providers.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $sitemap = $this->getContainer()->get('berriart_sitemap');

        $this->getContainer()->get('berriart_sitemap.provider.chain')->populate($sitemap);

        $gzip = $this->getContainer()->getParameter('berriart_sitemap.config.gzip');
        if ($gzip === true)
        {
            $directory = $this->getContainer()->getParameter('berriart_sitemap.config.gzip_dir');
            $filename = $this->getContainer()->getParameter('berriart_sitemap.config.gzip_file_pattern');

            if (!isset($directory) || trim($directory) === '')
            {
                $directory = './';
            }

            if (!isset($filename) || trim($filename) === '')
            {
                $output->writeln(sprintf('<comment>%s</comment>', 'Please specify gzip file pattern to use.'));
                return;
            }

            $this->createGzip($sitemap, $directory, $filename);
            $output->write('<info>Created gzip files!</info>', true);
        }

        $output->write('<info>Sitemap was sucessfully populated!</info>', true);
    }

    private function createGzip($sitemap, $directory, $filename)
    {
        $templating = $this->getContainer()->get('templating');
        $pages = $sitemap->pages();

        for ($page = 1; $page <= $pages; $page++)
        {
            $sitemap->setPage($page);
            $data = $templating->render('BerriartSitemapBundle:Sitemap:sitemap.xml.twig',
                array('sitemap' => $sitemap));

            $gzdata = gzencode($data, 9);
            $fp = fopen($directory . sprintf($filename, $page), "w");
            fwrite($fp, $gzdata);
        }
    }
}