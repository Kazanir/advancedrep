//<?php

/* To prevent PHP errors (extending class does not exist) revealing path */
if ( !defined( '\IPS\SUITE_UNIQUE_KEY' ) )
{
	exit;
}

class hook8 extends _HOOK_CLASS_
{

/* !Hook Data - DO NOT REMOVE */
public static function hookData() {
 return array_merge_recursive( array (
  'reputation' => 
  array (
    0 => 
    array (
      'selector' => 'div[data-controller=\'core.front.core.reputation\'].ipsClearfix',
      'type' => 'replace',
      'content' => '{{if $content instanceof \IPS\Content\Reputation and settings.reputation_enabled}}
    <div data-controller=\'core.front.core.reputation\' class=\'ipsClearfix {{if $extraClass}}{$extraClass}{{endif}}\'>
        {{if settings.reputation_point_types == \'like\'}}
            {{if $content->canGiveReputation( 1 ) || $content->canGiveReputation( -1 ) || $content->likeBlurb()}}
                <div class=\'ipsLikeRep ipsPos_right ipsResponsive_noFloat\'>
                    {{if $content->likeBlurb()}}
                        <span class=\'ipsLike_contents\'>{$content->likeBlurb()|raw}</span>
                    {{endif}}

                    {{if $content->canGiveReputation( 1 )}}
                        <a href=\'{$content->url( \'rep\' )->setQueryString( \'rep\', 1 )->csrf()}\' data-action="giveReputation" class=\'ipsButton ipsButton_like ipsButton_alternate\'><i class=\'fa fa-heart\'></i> <span class=\'ipsHide\' data-role=\'repCount\'>{$content->reputation()}</span> {lang="like"}</a>
                    {{endif}}
                    {{if $content->canGiveReputation( -1 )}}
                        <a href=\'{$content->url( \'rep\' )->setQueryString( \'rep\', -1 )->csrf()}\' data-action="giveReputation" class=\'ipsButton ipsButton_like ipsButton_veryLight\'><i class=\'fa fa-times\'></i> <span class=\'ipsHide\' data-role=\'repCount\'>{$content->reputation()}</span> {lang="unlike"}</a>
                    {{endif}}
                </div>
            {{endif}}
        {{else}}
            <div class=\'ipsLikeRep ipsPos_right\'>            
                {{if $content->canGiveReputation(1)}}
                    {{if !$content->repGiven()}}
                        <div class="ipsAdvancedRepModal ipsAdvancedRep_up">
                            {$content->advancedRepForm(1)|raw}
                        </div>
                        <a href=\'#\' title="Minus rep" data-ipsDialog data-ipsDialog-content=".ipsAdvancedRepModal.ipsAdvancedRep_up" data-ipsDialog-modal="true" data-ipsDialog-close="false" data-ipsDialog-size="narrow" class="ipsButton ipsButton_rep ipsButton_repUp"><i class="fa fa-arrow-up"></i></a>
                    {{else}}
                        <a href=\'{$content->url("rep")->setQueryString("rep", 1)->csrf()}\' data-action="giveReputation" class=\'ipsButton ipsButton_rep ipsButton_repUp\'><i class=\'fa fa-arrow-up\'></i></a>
                    {{endif}}
                {{endif}}
                {{if $content->canGiveReputation(-1)}}
				    {{if !$content->repGiven()}}
                        <div class="ipsAdvancedRepModal ipsAdvancedRep_down">
                            {$content->advancedRepForm(-1)|raw}
                        </div>
                        <a href=\'#\' title="Minus rep" data-ipsDialog data-ipsDialog-content=".ipsAdvancedRepModal.ipsAdvancedRep_down" data-ipsDialog-modal="true" data-ipsDialog-close="false" data-ipsDialog-size="narrow" class="ipsButton ipsButton_rep ipsButton_repDown"><i class="fa fa-arrow-down"></i></a>
				    {{else}}
                        <a href=\'{$content->url("rep")->setQueryString("rep", -1)->csrf()}\' data-action="giveReputation" class=\'ipsButton ipsButton_rep ipsButton_repDown\'><i class=\'fa fa-arrow-down\'></i></a>
					{{endif}}
                {{endif}}
                
                {{if settings.reputation_show_content}}
                    {{if member.group[\'gbw_view_reps\']}}
                        <a href=\'{$content->url( \'showRep\' )}\' data-ipsDialog data-ipsDialog-size=\'narrow\' data-ipsDialog-title=\'{lang="rep_log_title"}\' title=\'{lang="see_who_repped"}\' data-ipsTooltip class=\'ipsReputation_count ipsType_blendLinks {{if $content->reputation() < 0}}ipsType_negative{{elseif $content->reputation() > 0}}ipsType_positive{{else}}ipsType_neutral{{endif}}\'><i class=\'fa fa-heart ipsType_small\'></i> {$content->reputation()}</a>
                    {{else}}
                        <span class=\'ipsReputation_count ipsType_blendLinks {{if $content->reputation() < 0}}ipsType_negative{{elseif $content->reputation() > 0}}ipsType_positive{{else}}ipsType_neutral{{endif}}\'><i class=\'fa fa-heart ipsType_small\'></i> {$content->reputation()}</span>
                    {{endif}}
                {{endif}}
            </div>
        {{endif}}
    </div>
{{endif}}
',
    ),
  ),
), parent::hookData() );
}
/* End Hook Data */


}
