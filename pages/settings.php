<?php
$content =  "Hier kommen die Einstellungen hin...";

$fragment = new rex_fragment();
$fragment->setVar('title', $this->i18n('glossar_info_settings_title'));
$fragment->setVar('class', 'edit', true);
$fragment->setVar('body', $content, false);
echo '<div id="glossar">'.$fragment->parse('core/page/section.php').'</div>';


