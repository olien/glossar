<?php

  $func = rex_request('func', 'string');

if ($func == 'delete') {
  $id = (rex_request('id', 'int'));
  $sql = rex_sql::factory();
  // $sql->setDebug();

    $sql->setTable('rex_glossar');
    $sql->setWhere('id = ' . $id);
    if ($sql->delete()) {
      echo '<div class="alert alert-success">'.$this->i18n('aktuelles_deleted').'</div>';
    }

  $func = '';
}


  if ($func == '') {
    $list = rex_list::factory("SELECT * FROM rex_glossar ORDER BY term DESC");
    $list->addTableAttribute('class', 'table-striped');

    // ADD
    $thIcon = '<a href="'.$list->getUrl(['func' => 'add']).'"><i class="rex-icon rex-icon-add-action"></i></a>';
    $tdIcon = '<i class="rex-icon fa-file-text-o"></i>';
    $list->addColumn($thIcon, $tdIcon, 0, ['<th class="rex-table-icon" >###VALUE###</th>', '<td class="rex-table-icon">###VALUE###</td>']);

    // EDIT
    $list->setColumnParams($thIcon, ['func' => 'edit', 'id' => '###id###']);
    $list->setColumnParams('term', ['id' => '###id###', 'func' => 'edit']);

    // REMOVE
    $list->removeColumn('id');

    // layout
    $list->setColumnLayout('term', ['<th class="">###VALUE###</th>', '<td class="inactive">###VALUE###</td>']);


    // LABELs
    $list->setColumnLabel('status', $this->i18n('glossar_label_status'));
    $list->setColumnLabel('term', $this->i18n('glossar_label_term'));
    $list->setColumnLabel('definition', $this->i18n('glossar_label_definition'));
    $list->setColumnLabel('description', $this->i18n('glossar_label_description'));


    $list->addColumn('edit', '<i class="rex-icon rex-icon-edit"></i> ' . rex_i18n::msg('edit'));
    $list->setColumnLabel('edit', $this->i18n('aktuelles_function'));
    $list->setColumnLayout('edit', ['<th></th>', '<td class="rex-table-action">###VALUE###</td>']);
    $list->setColumnParams('edit', ['func' => 'edit', 'id' => '###id###']);


    $delete = 'deleteCol';
    $list->addColumn($delete, '<i class="rex-icon rex-icon-delete"></i> '.rex_i18n::msg('delete'), -1, ['<th></th>', '<td class="rex-table-action">###VALUE###</td>']);
    $list->setColumnParams($delete, ['id' => '###id###', 'func' => 'delete']);

    $list->addLinkAttribute($delete, 'data-confirm', rex_i18n::msg('delete') . ' ?');
    $list->removeColumn('id');


    $content = $list->get();

    $fragment = new rex_fragment();
    $fragment->setVar('title', $this->i18n('glossar_title'));
    $fragment->setVar('content', $content, false);
    $content = $fragment->parse('core/page/section.php');

    echo '<div id="glossar">'.$content.'</div>';

  } else if ($func == 'add' || $func == 'edit') {
    $id = rex_request('id', 'int');
    $form = rex_form::factory(rex::getTable('glossar'), '', 'id = ' . $id);

    $form->addParam('id', $id);
    // $form->setApplyUrl(rex_url::currentBackendPage());

    $form->setEditMode($func == 'edit');


    $title = $func == 'edit' ? $this->i18n('glossar_edit') : $this->i18n('glossar_add');

    $field = $form->addCheckboxField('status');
    $field->setLabel($this->i18n('glossar_label_status'));
    $field->addOption($this->i18n('glossar_status_aktiv'), $this->i18n('glossar_status_aktiv'));


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
