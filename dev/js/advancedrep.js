/**
 * advancedrep.js
 */
;( function($, _, undefined){
  "use strict";

  ips.controller.mixin('moveCommentToUrl', 'core.front.core.reputation', true, function () {
    this.initialize = function() {
      this.on('click', '[data-action="giveReputation"]', this.giveAdvancedReputation);
    };

    this.giveAdvancedReputation = function(e) {
      console.log(this);
      console.log(this.scope);
      console.log(this.scope.parent());

      this.giveReputation(e);
    }

  });
}(jQuery, _));
