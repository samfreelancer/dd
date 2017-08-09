function showConfirmationModal(deleteUrl, message){
    $('.confirmationModal').find('a.confirm').attr('href', deleteUrl);
    $('.confirmationModal').find('#message').text(message);
    $('.confirmationModal').modal();
}