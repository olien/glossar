<?php

namespace Glossar;
class Extension
{
  public static function clangAdded(\rex_extension_point $ep)
  {
    $firstLang = \rex_sql::factory();
    $firstLang->setQuery('SELECT * FROM ' . \rex::getTable('glossar') . ' WHERE clang_id=?', [\rex_clang::getStartId()]);
    $fields = $firstLang->getFieldnames();

    $newLang = \rex_sql::factory();
    $newLang->setDebug(false);
    foreach ($firstLang as $firstLangEntry) {
      $newLang->setTable(\rex::getTable('glossar'));

      foreach ($fields as $key => $value) {
        if ($value == 'pid') {
          echo '';
        } elseif ($value == 'active') {
          $newLang->setValue('active', 0);
        } elseif ($value == 'clang_id') {
          $newLang->setValue('clang_id', $ep->getParam('clang')->getId());
        } else {
          $newLang->setValue($value, $firstLangEntry->getValue($value));
        }
      }
    $newLang->insert();
  }
}

  public static function clangDeleted(\rex_extension_point $ep)
  {
    $deleteLang = \rex_sql::factory();
    $deleteLang->setQuery('DELETE FROM ' . \rex::getTable('glossar') . ' WHERE clang_id=?', [$ep->getParam('clang')->getId()]);
  }

  public static function glossarFormControlElement(\rex_extension_point $ep)
  {
    if (! \rex::getUser()->getComplexPerm('clang')->hasAll()) {
      $subject = $ep->getSubject();
        unset($subject['delete']);
        $ep->setSubject($subject);
      }
  }
}
