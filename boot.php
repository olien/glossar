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
  $page['glossar'] = ['title' => $this->i18n('glossar_title')];
  $this->setProperty('page', $page);

  /*
  $page = $this->getProperty('page');
  $page['subpages']['settings'] = ['title' => $this->i18n('glossar_settings'), 'perm' =>'glossar[settings]'];
  $this->setProperty('page', $page);
  */

  $page = $this->getProperty('page');
  $page['subpages']['info'] = ['title' => $this->i18n('glossar_info'), 'perm' =>'glossar[info]'];
  $page['subpages']['info']['subpages']['readme'] = ['title' => $this->i18n('glossar_info_readme')];
  if (rex::getUser()->isAdmin() OR rex::getUser()->hasPerm('glossar')) {
    $page['subpages']['info']['subpages']['modules'] = ['title' => $this->i18n('glossar_info_modules')];
  }
  $page['subpages']['info']['subpages']['changelog'] = ['title' => $this->i18n('glossar_info_changelog')];
  $page['subpages']['info']['subpages']['lizenz'] = ['title' => $this->i18n('glossar_info_licence')];
  $this->setProperty('page', $page);


    \rex_extension::register('CLANG_ADDED', '\Glossar\Extension::clangAdded');
    \rex_extension::register('CLANG_DELETED', '\Glossar\Extension::clangDeleted');

    rex_extension::register('PAGES_PREPARED', function () {



      $count_languages = \rex_clang::getAll();
      // echo count($count_languages);
      if (rex::getUser()->isAdmin() || rex::getUser()->hasPerm('glossar')) {
        $page = \rex_be_controller::getPageObject('glossar/main');
        $clang_id = \rex_clang::getCurrentId();
        $clang_name = \rex_clang::get($clang_id)->getName();

        $page->setSubPath(rex_path::addon('glossar', 'pages/main.php'));
        $current_page = rex_be_controller::getCurrentPage();

        if (count( $count_languages ) != 1) {
            foreach (\rex_clang::getAll() as $id => $clang) {
                if (rex::getUser()->getComplexPerm('clang')->hasPerm($id)) {
                    $page->addSubpage((new rex_be_page('clang' . $id, $clang->getName()))
                        ->setSubPath(rex_path::addon('glossar', 'pages/main.php'))
    ->setIsActive($id == $clang_id)
                    );
                }
            }
        } else {
            if (rex::getUser()->getComplexPerm('clang')->hasPerm($clang_id) ) {
              $page->addSubpage((new rex_be_page('clang' . $clang_id, $clang_name))
                ->setSubPath(rex_path::addon('glossar', 'pages/main.php'))
                ->setHidden(true)
              );
            }
        }



    }
  });



}



