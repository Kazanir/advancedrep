//<?php

/* To prevent PHP errors (extending class does not exist) revealing path */
if (!defined('\IPS\SUITE_UNIQUE_KEY')) {
  exit;
}

abstract class hook1 extends _HOOK_CLASS_
{

  /**
   * Give reputation
   *
   * @param	int					$type	1 for positive, -1 for negative
   * @param	\IPS\Member|NULL	$member	The member to check for (NULL for currently logged in member)
   * @return	void
   * @throws	\DomainException|\BadMethodCallException
   */
  public function giveReputation($type, \IPS\Member $member = NULL) {
    // We don't need to do any authentication here because failures in the
    // parent method (called in the middle) will throw an exception that
    // we don't catch.
    return call_user_func_array('parent::giveReputation', func_get_args());

    // Now we have to look up the rep that was just written and write our
    // additional rep data (the real amount based on the repping member's
    // power, and the message attached.)
    // @TODO: This is a placeholder message.
    $message = ($type > 0) ? "You received positive rep from %member! Never stop posting." : "You received negative rep from member! Post better.";
    // We also have to calculate our reputation amount based on the user's various attributes.
    $amount = rand(1,50) * $type;

    $condition_args = array(
      'app = ? AND type = ? AND member_id = ? AND type_id = ?',
      static::$application,
      static::$reputationType,
      $member->member_id,
      $this->{$idColumn}
    );
    $id = \IPS\Db::i()
      ->select('id', 'core_reputation_index', $condition_args)
      ->first();
    if ($id) {
      // The record was inserted successfully. This should always be true
      // because otherwise an exception in the parent will cause this code to
      // be skipped.
      $record = array(
        'rep_id' => $id,
        'amount' => $amount,
        'message' => $message,
        'seen' => 0,
        'hidden' => 0,
      );

      \IPS\Db::i()->insert('advancedrep_record', $record);
    }
  }
}
