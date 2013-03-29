<?php

namespace Berriart\Bundle\SitemapBundle\DependencyInjection;

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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class BerriartSitemapExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        
        $container->setParameter('berriart_sitemap.config.base_url', $config['base_url']);
        $container->setParameter('berriart_sitemap.config.alias', $config['alias']);
        $container->setParameter('berriart_sitemap.config.url_limit', $config['url_limit']);
        $container->setParameter('berriart_sitemap.config.gzip', array_key_exists('gzip', $config) ? $config['gzip'] : false);
        $container->setParameter('berriart_sitemap.config.gzip_dir', array_key_exists('gzip_dir', $config) ? $config['gzip_dir'] : '');
        $container->setParameter('berriart_sitemap.config.gzip_file_pattern', array_key_exists('gzip_file_pattern', $config) ? $config['gzip_file_pattern'] : '');
        $container->setParameter('berriart_sitemap.config.gzip_url', array_key_exists('gzip_url', $config) ? $config['gzip_url'] : '');
    }
}
