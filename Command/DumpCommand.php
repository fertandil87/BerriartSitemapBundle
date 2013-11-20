<?php

namespace Berriart\Bundle\SitemapBundle\Command;

/**
 * This file is part of the BerriartSitemapBundle package.
 *
 * (c) John Michael Luy <johnmichael.luy@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Berriart\Bundle\SitemapBundle\Manager\Sitemap;

class DumpCommand extends ContainerAwareCommand
{
    private $filesystem;

    protected function configure()
    {
        $this->setName('berriart:sitemap:dump')
            ->setDescription('Dump sitemap to files.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write('<info>Dumping Sitemap...</info>', true);

        $gzip = $this->getContainer()->getParameter('berriart_sitemap.config.dump_gzip');
        $directory = $this->getContainer()->getParameter('berriart_sitemap.config.dump_dir');
        $indexFile = $this->getContainer()->getParameter('berriart_sitemap.config.dump_index');
        $filename = $this->getFileNamePattern($gzip);

        $sitemap = $this->getContainer()->get('berriart_sitemap');

        $tempDir = $this->createTempDir();
        $this->writeSitemapFiles($sitemap, $tempDir, $indexFile, $filename, $gzip);
        $this->moveFiles($tempDir, $directory);

        $output->write('<info>Sitemap was successfully dumped!</info>', true);
    }

    protected function getFileSystem()
    {
        if (!isset($this->filesystem)) {
            $this->filesystem = new FileSystem();
        }

        return $this->filesystem;
    }

    protected function writeSitemapFiles(Sitemap $sitemap, $directory, $indexFile, $filename, $gzip)
    {
        $templating = $this->getContainer()->get('templating');
        $pages = $sitemap->pages();

        // create sitemap files
        for ($page = 1; $page <= $pages; $page++) {
            $sitemap->setPage($page);
            $data = $templating->render('BerriartSitemapBundle:Sitemap:sitemap.xml.twig',
                array('sitemap' => $sitemap));

            if ($gzip) {
                $data = gzencode($data, 9);
            }

            file_put_contents($directory . sprintf($filename, $page), $data);
        }

        //create index
        $urlPattern = $this->getContainer()->getParameter('berriart_sitemap.config.dump_url') . $filename;
        $data = $templating->render('BerriartSitemapBundle:Sitemap:sitemapindex.xml.twig', array(
            'pages' => $pages,
            'dump' => true,
            'url' => $urlPattern,
            'lastmod' => new \DateTime()
        ));

        file_put_contents($directory . $indexFile, $data);
    }

    protected function createTempDir()
    {
        $tempDir = sys_get_temp_dir() . '/BerriartSitemaps-' . uniqid() . '/';
        $this->getFileSystem()->mkdir($tempDir);
        return $tempDir;
    }

    protected function cleanup($dir)
    {
        $this->getFileSystem()->remove($dir);
    }

    protected function moveFiles($tempDir, $targetDir)
    {
        if (!is_writable($targetDir)) {
            $this->cleanup($tempDir);
            throw new \RuntimeException("Can't move sitemaps to $targetDir - directory is not writeable");
        }

        $this->getFileSystem()->mirror($tempDir, $targetDir, null, array('override' => true));
        $this->cleanup($tempDir);
    }

    protected function getFileNamePattern($gzip = false)
    {
        $filename = $this->getContainer()->getParameter('berriart_sitemap.config.dump_file_pattern');

        if ($gzip && !preg_match('/(.*)\.gz/', $filename)) {
            $filename = $filename . '.gz';
        }

        return $filename;
    }
}
