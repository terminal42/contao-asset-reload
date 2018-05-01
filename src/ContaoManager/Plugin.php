<?php

declare(strict_types=1);

/*
 * asset-reload extension for Contao Open Source CMS
 *
 * @copyright  Copyright (c) 2018, terminal42 gmbh
 * @author     terminal42 gmbh <info@terminal42.ch>
 * @license    LGPL-3.0+
 * @link       http://github.com/terminal42/contao-asset-reload
 */

namespace Terminal42\AssetReloadBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Dependency\DependentPluginInterface;
use Contao\ManagerPlugin\Routing\RoutingPluginInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Terminal42\AssetReloadBundle\Terminal42AssetReloadBundle;

class Plugin implements BundlePluginInterface, RoutingPluginInterface, DependentPluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(Terminal42AssetReloadBundle::class)->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteCollection(LoaderResolverInterface $resolver, KernelInterface $kernel)
    {
        if (!$kernel->isDebug()) {
            return null;
        }

        return $resolver->resolve(null, 'annotation')->load(dirname(__DIR__).'/Controller');
    }

    /**
     * Gets list of Composer packages names that must be loaded before this plugin.
     *
     * @return string[]
     */
    public function getPackageDependencies()
    {
        return ['contao/manager-bundle'];
    }
}
