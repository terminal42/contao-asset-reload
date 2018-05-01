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

namespace Terminal42\AssetReloadBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ManifestJsonController
{
    /**
     * @var null|string
     */
    private $manifestJson;

    /**
     * Constructor.
     *
     * @param string|null $manifestJson
     */
    public function __construct(string $manifestJson = null)
    {
        $this->manifestJson = $manifestJson;
    }


    /**
     * @Route("/_wdt/manifest_json")
     */
    public function __invoke(Request $request): Response
    {
        if (!$this->manifestJson) {
            return new Response('', Response::HTTP_NO_CONTENT);
        }

        $response = new JsonResponse();
        $response->setLastModified($this->getModificationTime());

        if ($request->headers->has('If-Modified-Since')) {
            set_time_limit(5);

            while(true) {
                if (!$response->isNotModified($request)) {
                    break;
                }

                $this->wait($response);
            }
        }

        $response->setData(json_decode(file_get_contents($this->manifestJson), true));
        $response->setStatusCode(Response::HTTP_OK);

        return $response;
    }

    private function getModificationTime(): \DateTime
    {
        clearstatcache(true, $this->manifestJson);
        return \DateTime::createFromFormat('U', (string) filemtime($this->manifestJson));
    }

    private function wait(Response $response)
    {
        usleep(500);

        if (connection_aborted()) {
            exit;
        }

        $response->setLastModified($this->getModificationTime());
    }
}
