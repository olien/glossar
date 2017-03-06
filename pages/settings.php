<?php

$content = '';
$buttons = '';

// Auf multiple Domain testen

if (rex_addon::exists('yrewrite')) {
    $yrewrite = new rex_yrewrite();
    $domains = $yrewrite::getDomains();
}

// Einstellungen speichern
if (rex_post('formsubmit', 'string') == '1') {
    if (rex_addon::exists('yrewrite')) {
        foreach ($domains as $domain) {
            if (!$domain->getId()) continue;
            $this->setConfig(rex_post('config', [
                ['article_'.$domain->getId(), 'string'],
            ]));
        }
    } else {
        $this->setConfig(rex_post('config', [
            ['article', 'string'],
        ]));
    }
    echo rex_view::success($this->i18n('config_saved'));
}

$content .= '<fieldset><legend>' . $this->i18n('glossar_info_settings_title') . '</legend>';


if (rex_addon::exists('yrewrite')) {
    foreach ($domains as $domain) {
  //      dump($domain->getId());
        
        if (!$domain->getId()) continue;
        // Glossar Artikel
        $formElements = [];
        $artname = '';
        $art = rex_article::get($this->getConfig('article_'.$domain->getId()));
        if ($art) {
            $artname = $art->getValue('name');
        }
        $n = [];
        $n['label'] = '<label for="REX_LINK_'.$domain->getId().'_NAME">' . $this->i18n('config_article') . ' - ' . $domain->getName() . '</label>';
        $n['field'] = '
        <div class="rex-js-widget rex-js-widget-link">
            <div class="input-group">	
                <input class="form-control" type="text" name="REX_LINK_NAME['.$domain->getId().']" value="' . $artname . '" id="REX_LINK_'.$domain->getId().'_NAME" readonly="readonly" />
                <input type="hidden" name="config[article_'.$domain->getId().']" id="REX_LINK_'.$domain->getId().'" value="' . $this->getConfig('article_'.$domain->getId()) . '" />
                <span class="input-group-btn">
                        <a href="#" class="btn btn-popup" onclick="openLinkMap(\'REX_LINK_'.$domain->getId().'\', \'&clang=1&category_id=1\');return false;" title="' . $this->i18n('var_link_open') . '"><i class="rex-icon rex-icon-open-linkmap"></i></a>
                        <a href="#" class="btn btn-popup" onclick="deleteREXLink('.$domain->getId().');return false;" title="' . $this->i18n('var_link_delete') . '"><i class="rex-icon rex-icon-delete-link"></i></a>
                </span>
            </div>
        </div>
        ';
        $formElements[] = $n;
        $fragment = new rex_fragment();
        $fragment->setVar('elements', $formElements, false);
        $content .= $fragment->parse('core/form/container.php');
    }
} else {

    // Glossar Artikel
    $formElements = [];
    $artname = '';
    $art = rex_article::get($this->getConfig('article'));
    if ($art) {
        $artname = $art->getValue('name');
    }
    $n = [];
    $n['label'] = '<label for="REX_LINK_1_NAME">' . $this->i18n('config_article') . '</label>';
    $n['field'] = '
    <div class="rex-js-widget rex-js-widget-link">
        <div class="input-group">	
            <input class="form-control" type="text" name="REX_LINK_NAME[1]" value="' . $artname . '" id="REX_LINK_1_NAME" readonly="readonly" />
            <input type="hidden" name="config[article]" id="REX_LINK_1" value="' . $this->getConfig('article') . '" />
            <span class="input-group-btn">
                    <a href="#" class="btn btn-popup" onclick="openLinkMap(\'REX_LINK_1\', \'&clang=1&category_id=1\');return false;" title="' . $this->i18n('var_link_open') . '"><i class="rex-icon rex-icon-open-linkmap"></i></a>
                    <a href="#" class="btn btn-popup" onclick="deleteREXLink(1);return false;" title="' . $this->i18n('var_link_delete') . '"><i class="rex-icon rex-icon-delete-link"></i></a>
            </span>
        </div>
    </div>
    ';
    $formElements[] = $n;
    $fragment = new rex_fragment();
    $fragment->setVar('elements', $formElements, false);
    $content .= $fragment->parse('core/form/container.php');
}


// Save-Button
$formElements = [];
$n = [];
$n['field'] = '<button class="btn btn-save rex-form-aligned" type="submit" name="save" value="' . $this->i18n('config_save') . '">' . $this->i18n('config_save') . '</button>';
$formElements[] = $n;
$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$buttons = $fragment->parse('core/form/submit.php');
$buttons = '
<fieldset class="rex-form-action">
    ' . $buttons . '
</fieldset>
';


// Ausgabe Formular
$fragment = new rex_fragment();
$fragment->setVar('class', 'edit');
$fragment->setVar('title', $this->i18n('config'));
$fragment->setVar('body', $content, false);
$fragment->setVar('buttons', $buttons, false);
$output = $fragment->parse('core/page/section.php');
$output = '
<form action="' . rex_url::currentBackendPage() . '" method="post">
<input type="hidden" name="formsubmit" value="1" />
    ' . $output . '
</form>
';
echo $output;
