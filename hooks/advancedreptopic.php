//<?php

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	exit;
}

class hook9 extends _HOOK_CLASS_
{
  public function _comments($class, $limit, $offset = NULL, $order = 'date DESC', $member = NULL, $includeHidden = NULL, $cutoff = NULL, $canViewWarn = NULL, $extraWhereClause = NULL) {
    $results = parent::_comments($class, $limit, $offset, $order, $member, $includeHidden, $cutoff, $canViewWarn, $extraWhereClause);

    /* Get the reputation stuff now so we don 't have to do lots of queries later */
    if (in_array('IPS\Content\Reputation', class_implements($class)) && count($results)) {
      /* Work out the query */
      $reputationWhere = array(array('app=? AND type=?', $class::$application, $class::$reputationType));
      $reputationWhere[] = array( \IPS\Db::i()->in('type_id', array_keys($results)));
      switch(\IPS\Settings::i()->reputation_point_types) {
        case 'positive':
        case 'like':
          $reputationWhere[] = array( 'rep_rating=?', "1" );
          break;
        case 'negative':
          $reputationWhere[] = array( 'rep_rating=?', "-1" );
          break;
      }

      /* If we need to display the "like blurb", we need the names of the people who have liked */
      if (\IPS\Settings::i()->reputation_point_types == 'like' and \IPS\Member::loggedIn()->group['gbw_view_reps']) {
        $names = array();
        $select = \IPS\Db::i()->select('core_reputation_index.type_id, core_reputation_index.member_id, core_reputation_index.rep_rating, core_members.name, core_members.members_seo_name', 'core_reputation_index', $reputationWhere, 'RAND()')->join('core_members', 'core_members.member_id=core_reputation_index.member_id', 'INNER');
      }

      /* Otherwise we just need the data */
      else {
        $select = \IPS\Db::i()->select('type_id, member_id, arr.amount AS rep_rating', array('core_reputation_index', 'cri'), $reputationWhere)->join(array('advancedrep_record', 'arr'), 'cri.id = arr.rep_id');
      }

      /* Populate */
      foreach ($select as $reputation) {
        $results[$reputation['type_id']]->reputation[$reputation['member_id']] = $reputation['rep_rating'];
      }

      return $results;
    }
  }
}
