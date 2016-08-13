<?php


$content =  "Hier kommen die Ausgaben hin...";

$fragment = new rex_fragment();
$fragment->setVar('title', $this->i18n('glossar_info_modules_title'));
$fragment->setVar('body', $content, false);
echo '<div id="glossar">'.$fragment->parse('core/page/section.php').'</div>';


