<?php

if (rex::isBackend() && rex::getUser()) {


  rex_extension::register('PACKAGES_INCLUDED', function () {
    if (rex::getUser() && $this->getProperty('compile')) {

      $compiler = new rex_scss_compiler();

      $scss_files = rex_extension::registerPoint(new rex_extension_point('BE_STYLE_SCSS_FILES', [$this->getPath('scss/master.scss')]));
      $compiler->setScssFile($scss_files);
      $compiler->setCssFile($this->getPath('assets/css/styles.css'));
      $compiler->compile();
      rex_file::copy($this->getPath('assets/css/styles.css'), $this->getAssetsPath('css/styles.css'));
        }
    });
  rex_view::addCssFile($this->getAssetsUrl('css/styles.css'));

  $page = $this->getProperty('page');
  $page['subpages']['glossar'] = ['title' => $this->i18n('glossar_title')];
  $this->setProperty('page', $page);

  $page = $this->getProperty('page');
  $page['subpages']['settings'] = ['title' => $this->i18n('glossar_settings'), 'perm' =>'glossar[settings]'];
  $this->setProperty('page', $page);

  $page = $this->getProperty('page');
  $page['subpages']['info'] = ['title' => 'Info', 'perm' =>'glossar[info]'];
  $page['subpages']['info']['subpages']['readme'] = ['title' => $this->i18n('glossar_info_readme')];
  $page['subpages']['info']['subpages']['modules'] = ['title' => $this->i18n('glossar_info_modules')];
  $page['subpages']['info']['subpages']['changelog'] = ['title' => $this->i18n('glossar_info_changelog')];
  $page['subpages']['info']['subpages']['lizenz'] = ['title' => $this->i18n('glossar_info_licence')];
  $this->setProperty('page', $page);

}
