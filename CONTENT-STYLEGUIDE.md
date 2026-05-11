# LogiAI Styleguide

**Voice, tone, and writing rules for logiai.blog**

Version 1.0 · Mai 2026

---

## 0. Hard Rules (gelten immer, ohne Ausnahme)

Bevor irgendetwas anderes greift, gelten diese Regeln. Sie werden nicht diskutiert, nicht stilistisch umgangen, nicht "in diesem einen Fall doch" gemacht.

1. **Keine em-dashes (`—`).** Nirgendwo. Stattdessen: Komma, Semikolon, Doppelpunkt, Punkt, oder Klammern.
2. **Keine en-dashes (`–`) im Fließtext.** Nur in Zahlenbereichen erlaubt: `2024–2026`, `Q1–Q4`. Sonst Bindestrich (`-`).
3. **Keine Emojis im redaktionellen Text.** Erlaubt in Social-Teasern und Newsletter-Eyebrows, nicht im Artikel.
4. **Keine generischen KI-Floskeln.** Verboten: "im Zeitalter von", "Game-Changer", "revolutionär", "transformative", "deep dive", "robust", "leverage", "synergistisch", "End-of-Day", "an der Spitze der Innovation".
5. **Keine doppelte Verneinung als Stil.** "Not unimportant", "nicht unwichtig" ist Schwächen-Verstecken. Wenn etwas wichtig ist, sag es.
6. **Kein "in der heutigen schnelllebigen Welt".** Wenn der Einstieg austauschbar ist, gehört er gelöscht.
7. **Keine LinkedIn-Sätze.** Sätze, die wie ein Tipp aus einem Karriere-Newsletter klingen ("Und das ist genau, was Führungskräfte heute brauchen."), sind sofort zu streichen.
8. **Keine deutsche Substantivierungsorgie.** "Die Umsetzung der Implementierung der Optimierung" ist Tod durch Genitiv. Aktiv, konkret, Verben.

---

## 1. Was LogiAI ist (in einem Satz)

LogiAI ist ein Analyse-Blog über KI in der Logistik für Operations-, Sales- und Tech-Leads, die zwischen Hype und Hands-on unterscheiden müssen.

Das heißt:
- **Leser:innen sind erwachsen.** Sie wissen, was ein TMS ist. Sie wissen, was MCP ist (oder schlagen es nach, wenn der Text es einmal sauber erklärt).
- **Sie kommen wegen einer Meinung,** nicht wegen Pressemitteilungen mit anderen Worten.
- **Sie bleiben wegen der Klarheit.** Wenn ein Stück nach drei Absätzen noch keine These hat, klicken sie weg.

---

## 2. Stilmix: 70 / 20 / 10

Der Hausstil besteht aus drei Komponenten. Jede hat ihren Anlass.

| Anteil | Stil | Vorbild | Wofür |
|---|---|---|---|
| 70% | **Analyse-Hausstil** | Stratechery (Ben Thompson) | Marktbewegungen, Deals, Trend-Stücke, alles mit These |
| 20% | **Erklär-Modus** | The Pragmatic Engineer | Tech-Erklärstücke (MCP, Agenten-Protokolle), HowTos, Prompt Templates, Vendor-Vergleiche |
| 10% | **Haltungs-Modus** | Republik | Aufmacher mit Position, kritische Branchen-Stücke, Skandale |

Mehr dazu in Abschnitt 4.

---

## 3. Tonalität: was LogiAI ist und was nicht

| LogiAI IST | LogiAI ist NICHT |
|---|---|
| analytisch | akademisch |
| scharf | zynisch |
| meinungsstark | belehrend |
| trocken-witzig | albern |
| respektvoll skeptisch | besserwisserisch |
| konkret | technokratisch |
| schnell auf den Punkt | hektisch |
| eigenständige Stimme | meinungsschwankend pro Quelle |

### Humor-Regeln

Humor ist erlaubt, aber sparsam und nur in zwei Geschmacksrichtungen:

1. **Trockene Pointe am Satzende.** "project44 hat einen Käufer gefunden für die Frage, die es selbst nicht beantworten konnte."
2. **Beobachtungs-Humor über die Branche.** "Drei Jahre lang waren autonome Trucks im Sunbelt zu Hause, weil im Sunbelt das Wetter mit selbstfahrenden Autos kooperiert."

Verboten:
- Wortwitze auf KI-Begriffen ("AI-Q ist niedrig").
- Anspielungen auf Logistik-Klischees ("die LKW-Branche, in der Männer noch Männer sind").
- Pop-Kultur-Referenzen ohne klaren Mehrwert ("wie bei Terminator 2").
- Selbstironie über den eigenen Blog ("aber wer liest schon noch Blogs").

---

## 4. Die drei Stile im Detail

### 4.1 Analyse-Hausstil (Stratechery-Schule), 70%

**Wann einsetzen:**
- Deal-Analysen (M&A, Partnerschaften, große Tender)
- Markt-Stücke (BCG-Reports, Vendor-Bewegungen)
- Trend-Stücke mit eigener These

**Strukturelle DNA:**
1. Aufmacher mit Setup-Punchline-Muster: "Die offizielle Geschichte ist X. Die eigentliche Geschichte ist Y."
2. Eine These, früh im Stück, klar formuliert
3. Beweis-Absätze, die die These prüfen
4. Implikations-Absatz: "Das heißt für [Operator / Verlader / Vendor]:"

**Eröffnungsformeln, die funktionieren:**
- "Das Spannende an X ist nicht Y. Es ist Z."
- "Die offizielle Geschichte über X ist eine Hype-Geschichte. Die eigentliche Geschichte ist eine andere."
- "X hat seit Jahren ein leises Problem: ..."
- "Drei Dinge haben sich zwischen 2024 und 2026 verschoben, und sie werden gerne verwechselt."

**Beispiel (DE):**

> Das Spannende am Deal project44 / LunaPath ist nicht der Preis. Es ist, was er über die Form des Visibility-Marktes verrät. Ein Jahrzehnt lang konkurrierten Visibility-Plattformen entlang einer Achse: mehr Sendungen, genauer, schneller verfolgen. project44 hat dieses Rennen gewonnen. Und nachdem es gewonnen hat, gibt es nun öffentlich zu, dass der Preis nicht reicht. Ein Dashboard, das eine Ausnahme zeigt, ist ein halbes Produkt. Ein System, das die Ausnahme löst, ist das ganze Produkt.

**Beispiel (EN):**

> The interesting thing about the project44 / LunaPath deal is not the price. It's what it tells you about the shape of the visibility market. For a decade, visibility platforms competed on one axis: more shipments tracked, more accurately, faster. project44 won that race. And now, having won it, it is admitting publicly that the prize is not enough. A dashboard that shows you the exception is half a product. A system that resolves the exception is the whole product.

---

### 4.2 Erklär-Modus (Pragmatic Engineer), 20%

**Wann einsetzen:**
- Tech-Erklärstücke (MCP, A2A, RAG, neue Modelle)
- HowTos, Prompt Templates
- Vendor-Vergleiche, Tool-Reviews
- Buyer's Guides für Operations-Teams

**Strukturelle DNA:**
1. Was es ist, in zwei Sätzen
2. Drei nummerierte Kernpunkte, jeder mit Bold-Lead
3. "Praktische Konsequenz / Praktisches Signal"
4. Ein konkreter nächster Schritt für die Leser:innen

**Eröffnungsformeln, die funktionieren:**
- "Hier ist, was man daraus mitnehmen sollte, und was nicht."
- "Drei Dinge haben sich verschoben. Sie werden gerne verwechselt."
- "X ist Y. Hier ist warum das praktisch relevant ist."

**Sprachliche Marker:**
- Bold-Leads in Listen: **Mitnehmen**, **Nicht mitnehmen**, **Erstens / Zweitens / Drittens**
- Konkrete Zahlen ohne Schmückung
- "Praktisches Signal" als Closer-Absatz
- Direkte Du-Ansprache erlaubt ("Stell die Frage bei der nächsten Verlängerung.")

**Beispiel (DE):**

> project44 hat ein KI-Agenten-Startup namens LunaPath.ai gekauft. Cash, angekündigt am 9. April. Hier ist, was man daraus mitnehmen sollte, und was nicht.
>
> **Mitnehmen**: Visibility-Plattformen konvergieren mit Execution-Plattformen. project44 trackt 1,5 Milliarden Sendungen pro Jahr. Das ist viel "wo ist mein Container". Es reicht allein nicht mehr.
>
> **Nicht mitnehmen**: Der Deal validiert nicht jedes KI-Agenten-Startup in der Logistik. project44 hat sechzehn Monate lang acht Anbieter geprüft. Eine Annahmequote von 12,5%.
>
> **Praktisches Signal**: Wenn euer TMS- oder Visibility-Anbieter Roadmap und Zeitplan für Agenten nicht beim Namen nennen kann, ist er im Rückstand.

---

### 4.3 Haltungs-Modus (Republik), 10%

**Wann einsetzen:**
- Branchen-Skandale, Missstände, Compliance-Themen
- Aufmacher-Stücke mit klarer Position
- Wenn die Recherche etwas Hartes zutage gefördert hat

**Strukturelle DNA:**
1. Stark-These im ersten oder zweiten Satz
2. Aufbau mit Härte, kein Diplomatie-Filter
3. Konkrete Namen, konkrete Zahlen, konkrete Folgen
4. Schluss als Verdikt, nicht als Ausweg

**Eröffnungsformeln, die funktionieren:**
- "Es gibt zwei Versionen dieser Geschichte. Die laute und die zutreffende."
- "Jahrelang hat die Branche X erzählt. Stimmt nicht."
- "Auf der Oberfläche ist das eine M&A-Notiz. In Wahrheit ist es das Eingeständnis einer ganzen Branche."

**Vorsicht:**
- Nicht jeder Artikel verträgt diesen Ton. Spar ihn für Stücke auf, die ihn rechtfertigen.
- Keine Polemik ohne Beleg. Wenn du eine harte These aufstellst, brauchst du im selben Stück mindestens drei stützende Fakten.

**Beispiel (DE):**

> project44 hat am 9. April 2026 LunaPath.ai gekauft, Cash, ohne langes Theater. Das ist an der Oberfläche eine M&A-Notiz, in Wahrheit das Eingeständnis einer ganzen Branche: Visibilität allein ist nichts mehr wert.

---

## 5. Satzbau und Rhythmus

### Satzlänge

Mische bewusst. Lange Analyse-Sätze, gefolgt von kurzen. Der Kontrast trägt die Aufmerksamkeit.

**Schlecht:** Drei Sätze hintereinander mit 28 Wörtern, alle mit Nebensatz.

**Gut:**
> Das Spannende am Deal ist nicht der Preis. Es ist, was er über die Form des Visibility-Marktes verrät, das seit einem Jahrzehnt entlang derselben Achse konkurriert. Mehr Sendungen, genauer, schneller. project44 hat dieses Rennen gewonnen. Jetzt kauft es das nächste.

### Aktiv statt Passiv

| Statt | Schreib |
|---|---|
| "Es wurde von project44 angekündigt..." | "project44 hat angekündigt..." |
| "Die Übernahme wird als strategisch bezeichnet..." | "project44 nennt die Übernahme strategisch..." |
| "Es ist davon auszugehen, dass..." | "Es spricht alles dafür, dass..." |

### Verben statt Substantivklumpen

| Statt | Schreib |
|---|---|
| "Die Umsetzung der Optimierung der Lieferkette" | "Die Lieferkette optimieren" |
| "Eine signifikante Reduktion der Durchlaufzeit" | "Die Durchlaufzeit deutlich gesenkt" |
| "Im Rahmen der Integration der Systeme" | "Beim Verbinden der Systeme" |

### Zahlen und Fakten

- Konkret, immer mit Quelle, oft als eigene Klausel: "1,5 Milliarden Sendungen pro Jahr. Über 1.000 Marken."
- Deutsche Zahlen mit Punkt als Tausendertrennzeichen, Komma als Dezimal: `12.400`, `1,4 km`.
- Englische Zahlen umgekehrt: `12,400`, `1.4 km`.
- Prozent ausschreiben oder mit Symbol, je nach Lesefluss: "12 Prozent" im Fließtext, "12%" in Tabellen.
- Keine fake-präzisen Zahlen ("ca. 73,4%"). Wenn es geschätzt ist, sag "rund 70%".

---

## 6. Eröffnungs- und Schlussformeln

### Gute Eröffnungen

Eine gute Eröffnung tut genau eins von drei Dingen:

1. **Behauptet etwas Überraschendes** ("Autonomes Trucking hat ein leises Problem: Es funktioniert, aber nur an den falschen Orten.")
2. **Stellt eine Frage neu** ("Die spannende Frage ist nicht mehr, ob autonome Trucks funktionieren. Sondern ob sie dort funktionieren, wo Geld verdient wird.")
3. **Setzt ein Datum / einen Fakt als Hebel** ("Am 9. April 2026 hat project44 LunaPath.ai gekauft. Das klingt nach einer M&A-Notiz, ist aber...")

### Schlechte Eröffnungen (sofort streichen)

- "In der heutigen schnelllebigen Welt der Logistik..."
- "KI verändert alles, und die Logistik macht da keine Ausnahme..."
- "Wer in der modernen Supply Chain bestehen will..."
- "Die Logistikbranche steht vor einer Revolution..."
- "Eine neue Studie zeigt..." (außer du folgst direkt mit dem überraschenden Befund)

### Gute Schlüsse

- **Verdikt** ("Was in dieser Branche jetzt kommt, kommt schnell: Visibility wird Execution, oder sie verschwindet.")
- **Konkrete Handlung** ("Plant eure Korridor-Strategie jetzt. Der Fahrermangel wartet nicht auf den nächsten Piloten.")
- **Offene Konsequenzfrage** ("Die nächste Frage ist nicht ob, sondern wer.")

### Schlechte Schlüsse

- "Die Zukunft wird zeigen, ..."
- "Eines ist klar: Die Logistik wird nie wieder dieselbe sein."
- "Wir bleiben dran und berichten weiter."
- "Was denkt ihr? Schreibt es in die Kommentare." (außer der Artikel ist explizit als Diskussionsstarter geframt)

---

## 7. Formatierung

### Headlines (H1)

- Maximal 12 Wörter
- Eine These, keine Frage (außer die Frage ist die Pointe)
- Konkrete Namen und Zahlen schlagen abstrakte Begriffe

| Schlecht | Gut |
|---|---|
| Die Zukunft der Logistik mit KI | project44 kauft LunaPath: Visibility wird Execution |
| Autonome Trucks im Aufwind | Kodiak fährt I-70: Autonomes Trucking verlässt den Sunbelt |
| MCP erklärt | MCP bei 97 Millionen Installs: Warum der Integrations-Layer wichtiger ist als die Modelle |

### Zwischenüberschriften (H2)

- Erzählen die Story als Skelett
- Wer nur die H2s liest, soll die These des Artikels kennen
- Keine generischen "Hintergrund", "Was bedeutet das?", "Fazit"

### Lead-Absatz (Dachzeile)

- Maximal 2 Sätze, kursiv
- Bringt die These und den konkreten Anlass
- Keine W-Fragen-Beantwortung im klassischen Nachrichtensinn

### Bold und Italic im Fließtext

- **Bold** nur bei Listenleads im Erklär-Modus ("**Mitnehmen**:", "**Erstens**:")
- *Italic* für Eigennamen von Produkten, Schiffen, Schiffen oder bei Hervorhebungen, die sonst untergehen würden
- Beides sparsam. Eine fett markierte Stelle pro Absatz, maximal.

### Listen

- Nur einsetzen, wenn die Information wirklich enumerativ ist
- Maximal 5 Bullets pro Liste
- Jedes Bullet ein vollständiger Satz, nicht ein einzelnes Wort

### Links

- Inline im Text, nie als "klicken Sie hier"
- Externe Links auf Originalquellen, nicht auf Aggregatoren
- Bei Quellenangaben am Artikelende: kurze Liste, kein Bibliothekarsformat

---

## 8. LogiAI-Spezifika

### Wie über Vendor:innen geschrieben wird

- **Beim Namen nennen, nicht verstecken.** project44, Maersk, Kodiak AI, DHL, FedEx. Konkret, nicht "ein führender Anbieter".
- **Keine PR-Floskeln übernehmen.** "Marktführend", "innovativ", "transformativ" werden nicht zitiert, sondern durch eigene Beobachtung ersetzt oder gestrichen.
- **Zitate von CEOs:** ja, aber mit Distanz. Wenn ein CEO sagt "AI agents become team members", paraphrasieren oder kurz zitieren und einordnen, nicht als Wahrheit stehen lassen.
- **Konflikte sichtbar machen.** Wenn ein Anbieter Kunde, Investor und Themenführer in einem Stück ist, gehört das hingewiesen.

### Wie über Studien geschrieben wird

- Immer mit Methodik: "BCG-Umfrage, Januar 2026, 180 Logistikdienstleister, DACH plus Nordamerika"
- Nie nur die Headline-Zahl zitieren. Eine zweite, einordnende Zahl gehört dazu
- Wenn die Studie vom Anbieter selbst kommt (Vendor-Sponsored Research), wird das gesagt

### Anglizismen

LogiAI ist zweisprachig (DE + EN). Im deutschen Text gilt:

- **Erlaubt und erwünscht:** TMS, WMS, ERP, Tender, Spot Rate, FTL/LTL, Carrier, Shipper, Visibility, Execution, Agent, Stack, Pipeline, Roadmap
- **Vermeiden, wo es deutsche Begriffe gibt:** "deliveries" → Lieferungen, "shipments" → Sendungen, "stakeholders" → Beteiligte
- **Niemals:** "deep-dive", "low-hanging fruit", "moving forward", "circling back", "touching base"

### Bilinguale Praxis

- DE und EN sind nicht Übersetzungen voneinander, sondern Eigenfassungen
- Idiome wechseln mit der Sprache: ein deutsches "ohne langes Theater" wird im Englischen nicht "without long theater"
- Zahlenformate folgen der Sprache (siehe Abschnitt 5)

---

## 9. Don'ts-Galerie (gesammelt, mit Korrektur)

### Don't 1: Em-dash-Sucht

❌ "project44 hat LunaPath gekauft — und das ist mehr als ein Deal — es ist ein Signal — die Branche verändert sich."

✅ "project44 hat LunaPath gekauft. Das ist mehr als ein Deal. Es ist ein Signal: Die Branche verändert sich."

### Don't 2: Substantivklumpen

❌ "Die Implementierung der Automatisierung der Kommissionierungsprozesse durch den Einsatz von KI-gesteuerten Robotik-Systemen."

✅ "KI-gesteuerte Roboter kommissionieren automatisch."

### Don't 3: Floskel-Eröffnung

❌ "Im heutigen schnelllebigen, digitalen Zeitalter, in dem KI nahezu jeden Aspekt unseres Lebens transformiert, steht die Logistikbranche vor beispiellosen Veränderungen."

✅ "Vier von zehn Verladern entscheiden inzwischen mit, ob ihr Logistikpartner KI nutzt. Das ist neu."

### Don't 4: Über-die-Schulter-blicken

❌ "In diesem Artikel werden wir zunächst betrachten, was MCP ist, dann erläutern, warum es wichtig ist, und abschließend einen Ausblick geben."

✅ Mit dem Argument loslegen. Niemand braucht eine Inhaltsangabe.

### Don't 5: Hedging bis zur Unlesbarkeit

❌ "Es könnte argumentiert werden, dass MCP potenziell eine durchaus signifikante Rolle spielen könnte, wenngleich dies natürlich auch von vielen Faktoren abhängt."

✅ "MCP ist die Integrationsschicht, die Agenten praktisch macht. Ob sie sich durchsetzt, hängt an drei Dingen."

### Don't 6: Pressemitteilungs-Echo

❌ "Wie das Unternehmen in einer Pressemitteilung verkündete, handelt es sich um einen bahnbrechenden Meilenstein in der Geschichte der autonomen Mobilität, der die gesamte Branche nachhaltig prägen wird."

✅ "Kodiak nennt die Fahrt einen Meilenstein. Die Fahrt war 90 Meilen lang, dauerte vier Stunden und verlief ohne Eingriff. Das ist die Tatsache. Was Meilenstein wirklich heißt, entscheidet sich in zwölf Monaten."

### Don't 7: KI-Mystifizierung

❌ "Die KI lernt, denkt, entscheidet und handelt wie ein menschlicher Mitarbeiter, nur schneller und besser."

✅ "Der Agent prüft drei Datenquellen, vergleicht das Ergebnis mit dem Regelwerk und schickt eine Buchungsbestätigung raus. Wenn das Regelwerk eine Ausnahme markiert, eskaliert er an einen Disponenten."

### Don't 8: Konferenz-Marketing

❌ "Die Zukunft der Supply Chain wird agentic, autonomous, und human-in-the-loop. Sind Sie bereit für den Wandel?"

✅ "Drei Begriffe tauchen 2026 in jeder Anbieter-Folie auf: agentic, autonomous, human-in-the-loop. Hier ist, was sie tatsächlich bedeuten und wo der Hype anfängt."

### Don't 9: Halbwissen mit Autorität

❌ "Studien zeigen, dass KI in der Logistik die Effizienz um bis zu 75% steigert."

✅ "Eine BCG-Umfrage vom Januar 2026 unter 180 Logistikdienstleistern fand: 13 Prozent berichten von messbarem finanziellen Impact durch KI. Die 75-Prozent-Zahl, die in Anbieter-Decks zirkuliert, stammt aus einer Vendor-Studie zu einem einzelnen Pilotstandort."

### Don't 10: Fake-Demut

❌ "Wir bei LogiAI sind keine Experten, aber wir versuchen, das Beste zu geben."

✅ Keine solche Zeile. Wenn du nicht weißt, wovon du redest, schreib das Stück nicht. Wenn du es weißt, sag es ohne Vorbehalt.

---

## 10. Pre-Publish-Checkliste

Bevor ein Stück live geht, gehe diese Liste durch. Wenn ein Punkt fehlt, bleibt das Stück im Draft.

### Inhalt

- [ ] These steht in den ersten drei Absätzen, klar und einzeilig formulierbar
- [ ] Wer nur H1 und H2s liest, kennt die These
- [ ] Mindestens drei konkrete Fakten (Zahlen, Namen, Daten) im Stück
- [ ] Mindestens eine Originalquelle verlinkt
- [ ] Jeder Vendor-Anspruch wird entweder belegt oder als Anspruch markiert
- [ ] Bei Studien: Methodik genannt (n, Zeitraum, Region, Auftraggeber)
- [ ] Implikations-Absatz vorhanden ("Das heißt für Operator / Verlader / Vendor:")

### Stil

- [ ] **Null em-dashes (`—`)** im gesamten Stück (Strg+F drüber)
- [ ] Keine generischen Floskeln aus Abschnitt 0 / 6 / 9
- [ ] Keine LinkedIn-Sätze
- [ ] Keine Substantiv-Genitiv-Ketten länger als drei Glieder
- [ ] Aktiv-Sätze überwiegen
- [ ] Eine Position pro Absatz, nicht drei
- [ ] Mindestens ein Satz unter 8 Wörtern (Rhythmus-Bruch)

### Form

- [ ] Headline maximal 12 Wörter, mit These oder Pointe
- [ ] Lead-Absatz kursiv, maximal 2 Sätze
- [ ] H2s erzählen die Story, nicht "Hintergrund / Fazit"
- [ ] Listen: nur wenn enumerativ, maximal 5 Bullets
- [ ] Bold und Italic sparsam
- [ ] Zahlenformat sprachkonsistent (DE: `1.500`, EN: `1,500`)
- [ ] Eigennamen korrekt geschrieben (project44 mit kleinem p, DB Schenker, Kühne+Nagel, A.P. Moller-Maersk)

### Zweisprachigkeit

- [ ] DE und EN sind je eigene Fassungen, nicht Übersetzungen
- [ ] Zahlenformate folgen der Sprache
- [ ] Idiome wechseln mit der Sprache
- [ ] Eigennamen und Produkte einheitlich (keine "project44" in DE und "Project44" in EN)

### SEO-Hygiene (ohne den Stil zu verbiegen)

- [ ] Headline enthält den zentralen Begriff oder Eigennamen
- [ ] Erster Absatz enthält die wichtigsten zwei bis drei Suchbegriffe natürlich
- [ ] Meta-Description maximal 155 Zeichen, eigenständig formuliert (keine Wiederholung des Lead-Absatzes)
- [ ] Alt-Text für Bilder beschreibend, nicht Keyword-Stuffing

---

## 11. Vorlagen pro Stück-Typ

### Vorlage A: Deal-Analyse (Stratechery-Modus)

```
H1: [Käufer] kauft [Ziel]: [die eigentliche Pointe in 4-6 Wörtern]

Lead (kursiv, 2 Sätze):
[Was passiert ist, eine Zeile. Was es bedeutet, eine Zeile.]

Absatz 1 (Setup-Punchline):
Das Spannende an [Deal] ist nicht [erwartete Lesart].
Es ist [echte Lesart, deine These].

Absatz 2 (Beweis 1):
[Konkreter Beleg mit Zahl oder Vorgeschichte.]

Absatz 3 (Beweis 2):
[Zweiter Beleg, idealerweise aus anderer Quelle.]

Absatz 4 (Implikation):
Das heißt für [Operator / Verlader / Vendor]:
1. [...]
2. [...]
3. [...]

Schluss (Verdikt, 2-3 Sätze):
[Was als Nächstes kommt. Keine Vorhersage, sondern eine Konsequenz.]
```

### Vorlage B: Tech-Erklärstück (Pragmatic Engineer)

```
H1: [Begriff / Technologie]: [konkreter Hebel in 4-6 Wörtern]

Lead (kursiv, 2 Sätze):
[Was es ist. Warum es jetzt zählt.]

Absatz 1:
[Was ist X, in zwei Sätzen ohne Jargon.]

H2: Was sich verschiebt
- **Erstens**: [...]
- **Zweitens**: [...]
- **Drittens**: [...]

H2: Was das praktisch heißt
[Konkretes Beispiel aus dem Logistik-Alltag.]

H2: Was zu fragen ist
[Die zwei Fragen, die Operations-Teams in der nächsten Vendor-Diskussion stellen sollten.]

Schluss:
[Ein konkreter nächster Schritt.]
```

### Vorlage C: Trend-Stück / Markt-Analyse (Stratechery / Republik-Mix)

```
H1: [Beobachtung]: [These in 5-8 Wörtern]

Lead (kursiv):
[Beobachtung in einer Zeile. These in einer Zeile.]

Absatz 1 (Aufschlag):
[Die offizielle Geschichte. Die eigentliche Geschichte.]

H2: Was die Zahlen sagen
[Drei bis fünf konkrete Datenpunkte mit Quelle.]

H2: Was die Zahlen nicht sagen
[Was unter dem Radar bleibt. Hier kommt die Haltung rein.]

H2: Was als Nächstes kommt
[Konkrete, beobachtbare Konsequenzen für 12-24 Monate.]

Schluss:
[Verdikt mit klarer Position.]
```

---

## 12. Quick-Reference-Karte

Für den Schreiballtag, zum Ausdrucken oder als Pin neben dem Screen.

```
EM-DASH       NEIN. Immer.
HUMOR         Trocken, am Satzende. Maximal einmal pro Stück.
THESE         Erste drei Absätze. Eine Zeile lang formulierbar.
ZAHLEN        Mit Quelle. Mit Methodik. Mit Einordnung.
ANBIETER      Beim Namen. Ohne PR-Worte.
AKTIV         Vor Passiv. Verb vor Substantiv.
H1            12 Wörter max. Mit Pointe oder Eigenname.
LEAD          Kursiv. 2 Sätze. Anlass + These.
SCHLUSS       Verdikt. Oder Handlung. Nie "bleibt spannend".
DON'T         "schnelllebige Welt", "Game-Changer", "deep dive".
```

---

## 13. Veränderungen pflegen

Dieser Styleguide ist v1.0. Er entwickelt sich mit dem Blog.

Wann der Guide angefasst wird:
- Wenn ein Stück erscheint, das gut funktioniert und gegen eine Regel verstößt, prüfen, ob die Regel zu eng ist
- Wenn neue Stück-Typen entstehen (Podcast-Transkripte, Video-Notizen), eigene Vorlagen ergänzen
- Wenn die Zielgruppe sich verschiebt, Abschnitt 1 nachschärfen

Versionierung im Git-Repo, eine Zeile Changelog pro Update.

---

*Ende des Styleguides. Wer hier angekommen ist, kann mit dem Schreiben anfangen.*
