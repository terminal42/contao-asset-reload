<?php

namespace Terminal42\AssetReloadBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Terminal42\AssetReloadBundle\Controller\ManifestJsonController;

class ManifestJsonPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $manifestPath = null;
        $config = $container->getExtensionConfig('framework');

        foreach (array_reverse($config) as $c) {
            if (isset($c['assets']['json_manifest_path'])) {
                $manifestPath = $c['assets']['json_manifest_path'];
                break;
            }
        }

        $container
            ->getDefinition(ManifestJsonController::class)
            ->setArgument(0, $manifestPath)
        ;
    }
}
