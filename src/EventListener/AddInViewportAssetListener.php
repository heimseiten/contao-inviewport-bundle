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
 * Bindet inViewport.js automatisch auf jeder Frontend-Seite ein – sowohl im
 * klassischen fe_page- als auch im modernen Twig-Layout. Ersetzt die manuelle
 * Aktivierung über das JavaScript-Template js_inviewport (tl_layout.scripts),
 * die das moderne Layout nicht mehr verarbeitet.
 *
 * Abgedeckt werden beide Render-Pfade:
 *  - generatePage-Hook  → klassisches fe_page (Contao 4.13 + 5.x)
 *  - LayoutEvent        → modernes Twig-Layout (Contao 5.x)
 *
 * Der Eintrag landet in $GLOBALS['TL_BODY'] (Body-Ende; im modernen Layout via
 * response_context.end_of_body) unter dem FESTEN Schlüssel
 * 'heimseiten_inviewport'. Das ist exakt derselbe Schlüssel, den andere Bundles
 * via Twig "{% add 'heimseiten_inviewport' to body %}" verwenden → das Script
 * wird automatisch dedupliziert und nie doppelt geladen.
 */
class AddInViewportAssetListener
{
    public function __construct(private readonly Packages $packages)
    {
    }

    #[AsHook('generatePage')]
    public function onGeneratePage(PageModel $pageModel, LayoutModel $layout): void
    {
        $this->addAsset();
    }

    /**
     * Reagiert auf das LayoutEvent des modernen Layouts.
     *
     * Bewusst als "object" typisiert und über config/services.yaml
     * (kernel.event_listener) registriert – NICHT per #[AsEventListener] –,
     * damit Contao\CoreBundle\Event\LayoutEvent auf Contao 4.13 (wo es die
     * Klasse nicht gibt) niemals aufgelöst wird und der Container dort nicht
     * bricht. Auf 4.13 wird das Event nie ausgelöst, die Methode also nie
     * aufgerufen.
     *
     * @param \Contao\CoreBundle\Event\LayoutEvent $event
     */
    public function onLayout(object $event): void
    {
        $this->addAsset();
    }

    private function addAsset(): void
    {
        $GLOBALS['TL_BODY']['heimseiten_inviewport'] = sprintf(
            '<script src="%s" defer></script>',
            htmlspecialchars($this->packages->getUrl('bundles/heimseitencontaoinviewport/inViewport.js'), ENT_QUOTES),
        );
    }
}
