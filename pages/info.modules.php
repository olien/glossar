<?php

$content =  "
<h3> Hier folgen noch einige Ausgaben und Erklärungen</h3>

Bis das fertig ist gibt es in dem Ordner

<pre>
/redaxo/src/addons/glossar/data/glossar_listenansicht_modulausgabe.php
</pre>

eine Modulausgabe (Listenansicht) welches auf einem Modul von Thomas Skerbis basiert.";

$fragment = new rex_fragment();
$fragment->setVar('title', $this->i18n('glossar_info_modules_title'));
$fragment->setVar('body', $content, false);
echo '<div id="glossar">'.$fragment->parse('core/page/section.php').'</div>';


