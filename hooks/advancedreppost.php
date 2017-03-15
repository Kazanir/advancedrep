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
    parent::giveReputation($type, $member);
    error_log("Made it to our function.");
    $member = $member ?: \IPS\Member::loggedIn();
    $idColumn = static::$databaseColumnId;

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
      $this->{$idColumn},
    );
    error_log("Just before select.");
    $stmt = \IPS\Db::i()
      ->select('id', 'core_reputation_index', $condition_args)
      ->setValueField('id');

    $id = FALSE;
    foreach ($stmt as $rep_id) {
      $id = $rep_id;
    }

    error_log("Got ID $id.");
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
      error_log("Made it to insertion.");
    }
  }

	/**
	 * Get reputation count
	 *
	 * @return	int
	 * @throws	\BadMethodCallException
	 */
  public function reputation() {
    if (!($this instanceof \IPS\Content\Reputation)) {
      throw new \BadMethodCallException;
    }

    if ($this->reputation === NULL)	{
      $rep_records = \IPS\Db::i()->select('cri.member_id AS member_id, arr.amount AS amount', array('core_reputation_index', 'cri'), $this->getReputationWhereClause())->join(array('advancedrep_record', 'arr'), 'cri.id = arr.rep_id')->setKeyField('member_id')->setValueField('amount');
      $this->reputation = iterator_to_array($rep_records);
      return array_sum( $this->reputation );
    }
    else {
      $repCount = 0;
      foreach($this->reputation as $member => $value) {
        if (is_int($value)) {
          $repCount += $value;
        }
        else {
          $repCount += $value['amount'];
        }
      }
      return $repCount;
    }
  }

	/**
	 * Can give reputation?
	 *
	 * @note	This method is also ran to check if a member can "unrep"
	 * @param	int					$type	1 for positive, -1 for negative
	 * @param	\IPS\Member|NULL	$member	The member to check for (NULL for currently logged in member)
	 * @return	bool
	 */
  public function canGiveReputation($type, \IPS\Member $member = NULL) {
    $repGiven = $this->repGiven($member);
    if ($repGiven != 0) {
      $repGiven = ($repGiven > 0 ? 1 : -1);
    }
		return static::_canGiveReputation($type, $this->author()->member_id, $this->author()->member_group_id, $repGiven, $member);
  }

  /**
   * Reputation message form, embedded in modal template.
   */
  public function advancedRepForm($rep_type) {
    // Omit the save button. We'll add links manually.
    $form = new \IPS\Helpers\Form('advancedrep_comment', FALSE);

    $form->add(new \IPS\Helpers\Form\Text(
      'advancedrep_rep_comment',
      FALSE,
      FALSE,
      ['placeholder' => 'Leave a comment for the author.'],
      NULL,
      '<div data-controller="plugins.advancedrep.comment">',
      '</div>'
    ));

    $classes = 'ipsButton ipsButton_rep ';
    $classes .= ($rep_type > 0) ? 'ipsButton_repUp' : 'ipsButton_repDown';
    $form->actionButtons[] = \IPS\Theme::i()->getTemplate('forms', 'core', 'global')->button(
      FALSE,
      'link',
      $this->url('rep')->setQueryString('rep', $rep_type)->csrf(),
      $classes,
      array(
        'data-action' => 'giveReputation',
      )
    );

    $classes = 'ipsButton ipsButton_rep ipsDialog_close';
    $form->actionButtons[] = \Ips\Theme::i()->getTemplate('forms', 'core', 'global')->button(
      FALSE,
      'link',
      '#',
      $classes,
      array(
        'data-action' => 'dialogClose',
      )
    );

    return (string) $form;
  }

}
