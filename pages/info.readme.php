<?php
$file = rex_file::get(rex_path::addon('glossar','README.md'));
$Parsedown = new Parsedown();

$content =  $Parsedown->text($file);

$fragment = new rex_fragment();
$fragment->setVar('title', $this->i18n('glossar_info_readme_title'));
$fragment->setVar('body', $content, false);
echo '<div id="glossar">'.$fragment->parse('core/page/section.php').'</div>';
