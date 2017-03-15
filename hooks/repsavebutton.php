//<?php

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	exit;
}

class hook10 extends _HOOK_CLASS_
{

/* !Hook Data - DO NOT REMOVE */
public static function hookData() {
 return array_merge_recursive( array (
  'button' => 
  array (
    0 => 
    array (
      'selector' => 'a[role=\'button\']',
      'type' => 'add_inside_start',
      'content' => '{{if $lang==\'\'}}
  {{if $class==\'ipsButton ipsButton_rep ipsButton_repUp\'}}
    <i class=\'fa fa-arrow-up\'></i>
  {{endif}}
  {{if $class==\'ipsButton ipsButton_rep ipsButton_repDown\'}}
    <i class=\'fa fa-arrow-down\'></i>
  {{endif}}
  {{if $class==\'ipsButton ipsButton_rep ipsDialog_close\'}}
    <i class=\'fa fa-ban\'></i>
  {{endif}}
{{endif}}',
    ),
  ),
), parent::hookData() );
}
/* End Hook Data */


}
