//<?php

$form = new \IPS\Helpers\Form('advancedrep_settings');

$form->add(new \IPS\Helpers\Form\Text('advancedrep_default_rep', \IPS\Settings::i()->advancedrep_default_rep));
$form->add(new \IPS\Helpers\Form\Text('advancedrep_regdate_factor', \IPS\Settings::i()->advancedrep_regdate_factor));
$form->add(new \IPS\Helpers\Form\Text('advancedrep_postcount_factor', \IPS\Settings::i()->advancedrep_postcount_factor));
$form->add(new \IPS\Helpers\Form\Text('advancedrep_userscore_factor', \IPS\Settings::i()->advancedrep_userscore_factor));
$form->add(new \IPS\Helpers\Form\Text('advancedrep_minimum_postcount', \IPS\Settings::i()->advancedrep_minimum_postcount));
$form->add(new \IPS\Helpers\Form\Text('advancedrep_minimum_userscore', \IPS\Settings::i()->advancedrep_minimum_userscore));

// try {
//   $groupSettingsJson = \IPS\Settings::i()->advancedrep_group_settings ?: '{}';
//   $groupSettings = json_decode($groupSettingsJson, TRUE);
//
//   $groups = \IPS\Member\Group::groups();
//   $columns = array(
//     'label' => function($key, $value) use ($groupSettings) {
//       error_log("Key: $key // Value: $value");
//
//     },
//       'minimum' => function($key, $value) use ($groupSettings) {
//         error_log("Key: $key // Value: $value");
//
//       },
//         'maximum' => function($key, $value) use ($groupSettings) {
//           error_log("Key: $key // Value: $value");
//
//         },
//         );
//
//
//   $rows = array();
//
//   foreach ($groups as $g) {
//     $rows[] = array(
//       'label' => $g->getName(),
//       'minimum' => 0,
//       'maximum' => 0,
//     );
//   }
//
//   $matrix = new \IPS\Helpers\Form\Matrix();
//   $matrix->columns = $columns;
//   $matrix->rows = $rows;
//
//   $form->add($matrix);
// }
// catch (Exception $e) {
//   error_log($e->getMessage());
// }

if ($values = $form->values())
{
	$form->saveAsSettings();
	return TRUE;
}

return $form;
