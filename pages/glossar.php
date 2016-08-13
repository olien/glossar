<?php
  $func = rex_request('func', 'string');
  $pid = rex_request('pid', 'int');

  if ($func == '') {
    $list = rex_list::factory("SELECT * FROM rex_glossar ORDER BY term DESC");
    $list->addTableAttribute('class', 'table-striped');

    // ADD
    $thIcon = '<a href="'.$list->getUrl(['func' => 'add']).'"><i class="rex-icon rex-icon-add-action"></i></a>';
    $tdIcon = '<i class="rex-icon fa-file-text-o"></i>';
    $list->addColumn($thIcon, $tdIcon, 0, ['<th class="rex-table-icon">###VALUE###</th>', '<td class="rex-table-icon">###VALUE###</td>']);

    // EDIT
    $list->setColumnParams($thIcon, ['func' => 'edit', 'pid' => '###pid###']);
    $list->setColumnParams('term', ['pid' => '###pid###', 'func' => 'edit']);

    // REMOVE
    $list->removeColumn('id');
    $list->removeColumn('pid');
    $list->removeColumn('clang_id');

    // LABELs
    $list->setColumnLayout('id', ['<th class="rex-table-id">###VALUE###</th>', '<td class="rex-table-id">###VALUE###</td>']);

    $list->setColumnLabel('term', $this->i18n('glossar_label_term'));
    $list->setColumnLabel('definition', $this->i18n('glossar_label_definition'));
    $list->setColumnLabel('description', $this->i18n('glossar_label_description'));

    $content = $list->get();

    $fragment = new rex_fragment();
    $fragment->setVar('title', $this->i18n('glossar_title'));
    $fragment->setVar('content', $content, false);
    $content = $fragment->parse('core/page/section.php');

    echo '<div id="glossar">'.$content.'</div>';

  } else if ($func == 'add' || $func == 'edit') {

    $form = rex_form::factory(rex::getTable('glossar'), '', 'pid = ' . $pid);
    $form->addParam('pid', $pid);
    $form->setApplyUrl(rex_url::currentBackendPage());
    //$form->setLanguageSupport('id', 'clang_id');

    $form->setEditMode($func == 'edit');


    $title = $func == 'edit' ? $this->i18n('glossar_edit') : $this->i18n('glossar_add');

    $field = $form->addTextField('term');
    $field->setLabel($this->i18n('glossar_label_term'));
    $field->getValidator()->add('notEmpty', $this->i18n('glossar_error_empty_term'));

    $field = $form->addTextareaField('definition', null);
    $field->setLabel($this->i18n('glossar_label_definition'));
    $field->getValidator()->add('notEmpty', $this->i18n('glossar_error_empty_definition'));

    $field = $form->addTextareaField('description', null);
    $field->setLabel($this->i18n('glossar_label_description'));


    $content = $form->get();

    $fragment = new rex_fragment();
    $fragment->setVar('class', 'edit', false);
    $fragment->setVar('title', $title);
    $fragment->setVar('body', $content, false);
    $content = $fragment->parse('core/page/section.php');

    echo '<div id="glossar">'.$content.'</div>';
  }
