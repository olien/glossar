<?php

if (!rex::isBackend()) {
  //if ($this->getConfig('status') != 'deaktiviert') {
    rex_extension::register('OUTPUT_FILTER', function(rex_extension_point $ep) {
      $content = $ep->getSubject();
      function GetBetween($var1="",$var2="",$pool){
        $temp1 = strpos($pool,$var1)+strlen($var1);
        $result = substr($pool,$temp1,strlen($pool));
        $dd=strpos($result,$var2);
        if($dd == 0){
          $dd = strlen($result);
        }
        return substr($result,0,$dd);
      }

    $header   = GetBetween("<!-- page::start -->","<!-- glossar::start -->",$content);
    $footer   = GetBetween("<!-- glossar::stop -->","<!-- page:end -->",$content);
    $content  = GetBetween("<!-- glossar::start -->","<!-- glossar::stop -->",$content);

    $query = "SELECT * FROM rex_glossar WHERE active = '1' ORDER BY term ASC ";
    $sql = rex_sql::factory();
    $sql->debugsql = 1;
    $sql->setQuery($query);

    if ($sql->getRows() != 0) {
      for ($i = 0; $i < $sql->getRows(); $i ++) {
        $marker = $sql->getValue('term');
        /*
        $url =  ""; //url_generate::getUrlById('rex_glossar', $sql->getValue('id'));
        $replace = '<a href="#hidden_content" class="boxer small button">'.$sql->getValue('begriff').'</a><div id="hidden_content" style="display: none;"><div class="inline_content"><h2>'.$sql->getValue('begriff').'</h2>'.$sql->getValue('text').'</div></div>';
        $replace = '<a href="'.rex_getUrl(43).'?tag_id=' . $sql->getValue('id') . '"><abbr class="glossarlink" title="<b>'.$sql->getValue('term').'</b><br/>'.$sql->getValue('definition').'" rel="tooltip">'.$sql->getValue('term').'</abbr></a>';
        */
        $replace = '<abbr class="glossarlink" title="'.$sql->getValue('definition').'" rel="tooltip">'.$sql->getValue('term').'</abbr>';
        $markers = explode('|', $marker);
        foreach ($markers as $search) {
          $regEx ='\'(?!((<.*?)|((<a.*?)|(<h.*?))))(\b'. $search .'\b)(?!(([^<>]*?)>)|([^>]*?(</a>|</h.*?>)))\'si';
          $content = preg_replace($regEx,$replace,$content,1);
        }
        $sql->next();
      }
    }
    return $header.$content.$footer;
    });
   //}
}

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

    $page = $this->getProperty('page');
    $page['subpages']['settings'] = ['title' => $this->i18n('glossar_settings'), 'perm' =>'glossar[settings]'];
    $this->setProperty('page', $page);

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
    if (rex::getUser()->isAdmin() || rex::getUser()->hasPerm('glossar[general]')) {
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
            ->setIsActive($id == $clang_id));
          }
        }
      } else {
        if (rex::getUser()->getComplexPerm('clang')->hasPerm($clang_id) ) {
          $page->addSubpage((new rex_be_page('clang' . $clang_id, $clang_name))
          ->setSubPath(rex_path::addon('glossar', 'pages/main.php'))
          ->setHidden(true));
        }
      }
    }
  });
}



