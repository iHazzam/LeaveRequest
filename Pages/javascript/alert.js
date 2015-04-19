//this function controls the popover for the "how many to approve" notification for managers
//(On all "displayed" pages when user is a manager)
$(document).ready(function() {
  $('#approve').tooltip();
  $('[data-toggle="popover"]').popover({trigger: 'hover','placement': 'right'});
});
