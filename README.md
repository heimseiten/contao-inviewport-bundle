# inViewport
Jedem Element, welches die Klasse ivp (in view port) hat, werden folgende Klassen hinzugefügt:

- iivp (is in view port)
- wivp (was in view port)
- above_viewport
- below_viewport
- over_half_ivp

Zudem wird dem Body-Tag die Klasse ivp_active hinzugefügt, wenn die Funktionalität aktiv ist.

---

## Installation

```
composer require heimseiten/contao-inviewport-bundle
```

Das JavaScript wird ab Version 3.2 **automatisch** auf jeder Frontend-Seite eingebunden – sowohl im klassischen `fe_page`- als auch im modernen Twig-Layout. Eine manuelle Einbindung bzw. das Aktivieren des JavaScript-Templates ist dafür **nicht mehr nötig**.

Die mitgelieferten Standard-Styles (Einblende-Effekte) sind optional und lassen sich bei Bedarf über *Seitenlayout → JavaScript → JavaScript-Templates → `js_inviewport`* aktivieren.

---

Mit folgenden Klassen, plus der Klasse ivp, könnnen Elemente eingeblendet werden:

- einblenden
- einblendenVonUnten
- einblendenVonOben
- einblendenVonRechts
- einblendenVonLinks
- einblendenVonHinten
- einblendenVonVorne
