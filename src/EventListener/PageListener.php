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

namespace Terminal42\AssetReloadBundle\EventListener;

use Symfony\Component\Asset\Packages;

class PageListener
{
    /**
     * @var Packages
     */
    private $packages;

    /**
     * @var bool
     */
    private $debug;

    /**
     * Constructor.
     *
     * @param Packages    $packages
     * @param bool        $debug
     */
    public function __construct(Packages $packages, bool $debug)
    {
        $this->packages = $packages;
        $this->debug = $debug;
    }

    public function onGeneratePage(): void
    {
        if (!$this->debug) {
            return;
        }

        $GLOBALS['TL_BODY'][] = sprintf(
            '<script src="%s"></script>',
            $this->packages->getUrl('asset-reload.js', 'terminal42_asset_reload')
        );
    }
}
