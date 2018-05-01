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

namespace Terminal42\AssetReloadBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Terminal42\AssetReloadBundle\DependencyInjection\Compiler\ManifestJsonPass;

class Terminal42AssetReloadBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ManifestJsonPass());
    }
}
