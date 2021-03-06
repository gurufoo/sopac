<?php

$uri = $_GET['q'];
$getvars = sopac_parse_get_vars();

?>
  <div id="sidetreecontrol"><a href="?#">Collapse All</a> | <a href="?#">Expand All</a></div>
  <ul id="facet" class="treeview">
<?php
$mat_count = count($locum_result['facets']['mat']);
$search_formats = is_array($getvars['search_format']) ? $getvars['search_format'] : array();
if ($mat_count) {
  if (!is_array($getvars['search_format']) || strtolower($_GET['search_format']) == 'all') {
    $li_prop = ' class="closed"';
  }
  else {
    $li_prop = NULL;
  }
  print "<li$li_prop><span class=\"folder\">by Format</span> <small>($mat_count)</small><ul>\n";
  foreach ($locum_result['facets']['mat'] as $mat_code => $mat_code_count) {
    if (in_array($mat_code, $search_formats)) {
      print '<li id="tree-kid" class="facet-item-selected"><strong>» ' . $locum_config['formats'][$mat_code] . "</strong></li>\n";
    }
    else {
      $getvars_tmp = $getvars;
      if ($getvars_tmp['search_format'][0] == 'all') {
        unset($getvars_tmp['search_format'][0]);
      }
      $getvars_tmp['search_format'][] = $mat_code;
      if (isset($getvars_tmp['page'])) {
        $getvars_tmp['page'] = '';
      }
      $link = l($locum_config['formats'][$mat_code], $uri, array('query' => sopac_make_pagevars(sopac_parse_get_vars($getvars_tmp))));
      print '<li id="tree-kid">» ' . $link . ' <small>(' . $mat_code_count . ")</small></li>\n";
      unset($getvars_tmp);
    }
  }
  print "</ul></li>\n";
}

if (variable_get('sopac_multi_branch_enable', 0)) {
  $facet_avail = $getvars['limit_avail'] ? $getvars['limit_avail'] : NULL;
  $avail_count = count($locum_result['facets']['avail']);
  if ($avail_count) {
    if (!$getvars['limit_avail'] && $getvars['limit_avail'] != 'any' ) {
      $li_prop = ' class="closed"';
    }
    else {
      $li_prop = NULL;
    }
    print "<li$li_prop><span class=\"folder\">by Availability</span> <small>($avail_count)</small><ul>\n";
    foreach ($locum_result['facets']['avail'] as $avail => $avail_count_indv) {
      $avail_name = $locum_config['branches'][$avail] ? $locum_config['branches'][$avail] : $avail;
      if ($avail_name == 'any') {
        $avail_name = t('Any Location');
      }
      if ($avail == $facet_avail) {
        print '<li id="tree-kid" class="facet-item-selected"><strong>» ' . $avail_name . "</strong></li>\n";
      }
      else {
        $getvars_tmp = $getvars;
        $getvars_tmp['limit_avail'] = urlencode($avail);
        if (isset($getvars_tmp['page'])) {
          $getvars_tmp['page'] = '';
        }
        $link = l($avail_name, $uri, array('query' => sopac_make_pagevars(sopac_parse_get_vars($getvars_tmp))));
        print '<li id="tree-kid">» ' . $link . ' <small>(' . $avail_count_indv . ")</small></li>\n";
        unset($getvars_tmp);
      }
    }
    print "</ul></li>\n";

  }
}

$facet_age = is_array($getvars['age']) ? $getvars['age'] : array();
$age_count = count($locum_result['facets']['ages']);
if ($age_count) {
  if (!is_array($getvars['age'])) {
    $li_prop = ' class="closed"';
  }
  else {
    $li_prop = NULL;
  }
  print "<li$li_prop><span class=\"folder\">by Age Group</span> <small>($age_count)</small><ul>\n";
  foreach ($locum_result['facets']['ages'] as $age => $age_count_indv) {
    $age_name = $locum_config['ages'][$age] ? $locum_config['ages'][$age] : $age;
    if (in_array($age, $facet_age)) {
      print '<li id="tree-kid" class="facet-item-selected"><strong>» ' . $age_name . "</strong></li>\n";
    }
    else {
      $getvars_tmp = $getvars;
      $getvars_tmp['age'][] = urlencode($age);
      if (isset($getvars_tmp['page'])) {
        $getvars_tmp['page'] = '';
      }
      $link = l($age_name, $uri, array('query' => sopac_make_pagevars(sopac_parse_get_vars($getvars_tmp))));
      print '<li id="tree-kid">» ' . $link . ' <small>(' . $age_count_indv . ")</small></li>\n";
      unset($getvars_tmp);
    }
  }
  print "</ul></li>\n";

}

$facet_series = is_array($getvars['facet_series']) ? $getvars['facet_series'] : array();
if (count($locum_result['facets']['series'])) {
  foreach ($locum_result['facets']['series'] as $series => $series_count) {
    $ser_arr = explode(';', $series);
    $ser_clean = trim($ser_arr[0]);
    //$series_result_unweeded[$ser_clean]++;
    if ($series_count >= 1) {
      $series_result[$ser_clean] += $series_count;
    }
  }
  $series_count = count($series_result);
  if ($series_count) {
    if (!is_array($getvars['facet_series'])) {
      $li_prop = ' class="closed"';
    }
    else {
      $li_prop = NULL;
    }
    print "<li$li_prop><span class=\"folder\">by Series</span> <small>($series_count)</small><ul>\n";
    foreach ($series_result as $series => $series_name_count) {
      if (in_array($series, $facet_series)) {
        print '<li id="tree-kid" class="facet-item-selected"><strong>» ' . $series . "</strong></li>\n";
      }
      else {
        $getvars_tmp = $getvars;
        $getvars_tmp['facet_series'][] = urlencode($series);
        if (isset($getvars_tmp['page'])) {
          $getvars_tmp['page'] = '';
        }
        $link = l($series, $uri, array('query' => sopac_make_pagevars(sopac_parse_get_vars($getvars_tmp))));
        print '<li id="tree-kid">» ' . $link . ' <small>(' . $series_name_count . ")</small></li>\n";
        unset($getvars_tmp);
      }
    }
    print "</ul></li>\n";
  }
}

$facet_lang = is_array($getvars['facet_lang']) ? $getvars['facet_lang'] : array();
$lang_count = count($locum_result['facets']['lang']);
if ($lang_count) {
  if (!is_array($getvars['facet_lang'])) {
    $li_prop = ' class="closed"';
  }
  else {
    $li_prop = NULL;
  }
  print "<li$li_prop><span class=\"folder\">by Language</span> <small>($lang_count)</small><ul>\n";
  foreach ($locum_result['facets']['lang'] as $lang => $lang_code_count) {
    if (in_array($lang, $facet_lang)) {
      print '<li id="tree-kid" class="facet-item-selected"><strong>» ' . ucfirst($locum_config['languages'][$lang]) . "</strong></li>\n";
    }
    else {
      $getvars_tmp = $getvars;
      $getvars_tmp['facet_lang'][] = urlencode($lang);
      if (isset($getvars_tmp['page'])) {
        $getvars_tmp['page'] = '';
      }
      $link = l(ucfirst($locum_config['languages'][$lang]), $uri, array('query' => sopac_make_pagevars(sopac_parse_get_vars($getvars_tmp))));
      print '<li id="tree-kid">» ' . $link . ' <small>(' . $lang_code_count . ")</small></li>\n";
      unset($getvars_tmp);
    }
  }
  print "</ul></li>\n";

}

$facet_year = is_array($getvars['facet_year']) ? $getvars['facet_year'] : array();
$year_count = count($locum_result['facets']['pub_year']);
if ($year_count) {
  if (!is_array($getvars['facet_year'])) {
    $li_prop = ' class="closed"';
  }
  else {
    $li_prop = NULL;
  }
  print "<li$li_prop><span class=\"folder\">by Pub. Year</span> <small>($year_count)</small><ul>\n";
  foreach ($locum_result['facets']['pub_year'] as $year => $pub_year_count) {
    if (in_array($year, $facet_year)) {
      print '<li id="tree-kid" class="facet-item-selected"><strong>» ' . $year . "</strong></li>\n";
    }
    elseif ($year <= date('Y')) {
      $getvars_tmp = $getvars;
      $getvars_tmp['facet_year'][] = urlencode($year);
      if (isset($getvars_tmp['page'])) {
        $getvars_tmp['page'] = '';
      }
      $link = l($year, $uri, array('query' => sopac_make_pagevars(sopac_parse_get_vars($getvars_tmp))));
      print '<li id="tree-kid">» ' . $link . ' <small>(' . $pub_year_count . ")</small></li>\n";
      unset($getvars_tmp);
    }
  }
  print "</ul></li>\n";

}

$facet_decade = is_array($getvars['facet_decade']) ? $getvars['facet_decade'] : array();
$decade_count = count($locum_result['facets']['pub_decade']);
if ($decade_count) {
  if (!is_array($getvars['facet_decade'])) {
    $li_prop = ' class="closed"';
  }
  else {
    $li_prop = NULL;
  }
  print "<li$li_prop><span class=\"folder\">by Decade</span> <small>($decade_count)</small><ul>\n";
  foreach ($locum_result['facets']['pub_decade'] as $decade => $pub_decade_count) {
    if (in_array($decade, $facet_decade)) {
      print '<li id="tree-kid" class="facet-item-selected"><strong>» ' . $decade . "-" . ($decade + 9) . "</strong></li>\n";
    }
    elseif ($decade <= date('Y')) {
      $getvars_tmp = $getvars;
      $getvars_tmp['facet_decade'][] = urlencode($decade);
      if (isset($getvars_tmp['page'])) {
        $getvars_tmp['page'] = '';
      }
      $link = l($decade . '-' . ($decade + 9), $uri, array('query' => sopac_make_pagevars(sopac_parse_get_vars($getvars_tmp))));
      print '<li id="tree-kid">» ' . $link . ' <small>(' . $pub_decade_count . ")</small></li>\n";
      unset($getvars_tmp);
    }
  }
  print "</ul></li>\n";
}

/* Uncomment for subjects facet
$facet_subject = is_array($getvars['facet_subject']) ? $getvars['facet_subject'] : array();
$subject_count = count($locum_result['facets']['subject']);
if ($subject_count) {
  if (!is_array($getvars['facet_subject'])) {
    $li_prop = ' class="closed"';
  }
  else {
    $li_prop = NULL;
  }
  print "<li$li_prop><span class=\"folder\">by Subject</span> <small>($subject_count)</small><ul>\n";
  foreach ($locum_result['facets']['subject'] as $subject => $subject_code_count) {
    if (in_array($subject, $facet_subject)) {
      print '<li id="tree-kid" class="facet-item-selected"><strong>» ' . $subject . "</strong></li>\n";
    }
    else {
      $getvars_tmp = $getvars;
      $getvars_tmp['facet_subject'][] = urlencode($subject);
      if (isset($getvars_tmp['page'])) {
        $getvars_tmp['page'] = '';
      }
      $link = l($subject, $uri, array('query' => sopac_make_pagevars(sopac_parse_get_vars($getvars_tmp))));
      print '<li id="tree-kid">» ' . $link . ' <small>(' . $subject_code_count . ")</small></li>\n";
      unset($getvars_tmp);
    }
  }
  print "</ul></li>\n";
}
*/

?>
  </ul>
