<?php

declare(strict_types=1);

/*
 * This file is part of inViewport.
 *
 * (c) Niels Hegmans 2020 <info@heimseiten.de>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/heimseiten/contao-inviewport-bundle
 */

namespace Heimseiten\ContaoInviewportBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\LayoutModel;
use Contao\PageModel;
use Symfony\Component\Asset\Packages;

/**
 * Bindet inViewport.js UND inviewport.css automatisch auf jeder Frontend-Seite
 * ein – im klassischen (fe_page) wie im modernen Twig-Layout. Ersetzt das alte
 * js_inviewport.html5, das nur über tl_layout.scripts lief und vom modernen
 * Layout nicht mehr verarbeitet wird.
 *
 * generatePage feuert nur im klassischen Pfad, LayoutEvent nur im modernen –
 * daher beide. JS landet in TL_BODY (Body-Ende), CSS in TL_CSS (<head>), jeweils
 * unter dem festen Schlüssel 'heimseiten_inviewport' → automatische Dedup mit
 * Bundles, die per Twig "{% add 'heimseiten_inviewport' to ... %}" dasselbe tun.
 */
class AddInViewportAssetListener
{
    public function __construct(private readonly Packages $packages)
    {
    }

    #[AsHook('generatePage')]
    public function onGeneratePage(PageModel $pageModel, LayoutModel $layout): void
    {
        $this->addAssets();
    }

    /**
     * LayoutEvent des modernen Layouts. Bewusst als "object" typisiert und über
     * config/services.yaml (kernel.event_listener) registriert – NICHT per
     * Attribut –, damit Contao\CoreBundle\Event\LayoutEvent auf Contao 4.13
     * (ohne diese Klasse) nie aufgelöst werden muss.
     *
     * @param \Contao\CoreBundle\Event\LayoutEvent $event
     */
    public function onLayout(object $event): void
    {
        $this->addAssets();
    }

    private function addAssets(): void
    {
        $base = 'bundles/heimseitencontaoinviewport/';

        // JS ans Body-Ende (fragt die .ivp-Elemente sofort ab → defer, damit der DOM steht)
        $GLOBALS['TL_BODY']['heimseiten_inviewport'] = sprintf(
            '<script src="%s" defer></script>',
            htmlspecialchars($this->packages->getUrl($base.'inViewport.js'), ENT_QUOTES),
        );

        // CSS in den <head>
        $GLOBALS['TL_CSS']['heimseiten_inviewport'] = $base.'inviewport.css|static';
    }
}
