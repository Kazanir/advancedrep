/**
 * advancedrep.js
 */
;( function($, _, undefined){
  "use strict";

  ips.controller.mixin('moveCommentToUrl', 'core.front.core.reputation', true, function () {
    var controller = this;

    this.initialize = function() {
      $(document).one('click', 'div.ipsAdvancedRepModal [data-action="giveReputation"]', this.giveAdvancedReputation);
      // this.on('click', '[data-action="giveReputation"]', this.giveAdvancedReputation);
    };

    this.giveAdvancedReputation = function(e) {
      comment = $(document).find(":input[name='advancedrep_rep_comment']").val();
      controller;
      debugger;
      if (comment) {
        var n = comment.search("&advancedrep_comment=");
        if (n != -1) {
          comment = comment.slice(0, n);
        }
        this.href += "&advancedrep_comment=" + encodeURIComponent(comment);
      }
      // controller.giveReputation(e);
    }

  });
}(jQuery, _));
