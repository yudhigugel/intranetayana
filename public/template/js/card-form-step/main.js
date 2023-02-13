$(document).ready(function(){

  var container = $(".uic-wrapper");
  var nextBtn = $("nav .btn-next");
  var backBtn = $("nav .btn-back");
  var finishBtn = $("nav .btn-finish");

  updateNav();

  function updateNav(){
    var hasAnyRemovedCard = $(".toRight").length ? true : false,
        isCardLast = $(".card-middle").length ? false : true;

    if(hasAnyRemovedCard) {
      backBtn.removeClass('back-btn-hide');

    } else {
      backBtn.addClass('back-btn-hide');
      $(".card-front").addClass('noBack');
    }

    if(isCardLast){
      nextBtn.hide();
      finishBtn.removeClass("hide");
    } else {
      nextBtn.show();
      finishBtn.addClass("hide");
    }
  }

  function showNextCard(){
    //Check if there is only one card left
    if($(".card-middle").length > 0){
      var currentCard = $(".card-front"),
        middleCard = $(".card-middle"),
        backCard = $(".card-back"),
        outCard = $(".card-out").eq(0);

      //Remove the front card
      currentCard.removeClass('card-front').addClass('toRight');
      //change the card places
      middleCard.removeClass('card-middle').addClass('card-front');
      backCard.removeClass('card-back').addClass('card-middle');
      outCard.removeClass('card-out').addClass('card-back');

      updateNav();
    }
  }

  function showPreviousCard(hide=true){
    var currentCard = $(".card-front"),
        middleCard = $(".card-middle"),
        // backCard = $(".card-back"),
        lastRemovedCard = $(".toRight").slice(-1);
    if(lastRemovedCard.length == 0){
      lastRemovedCard.removeClass('toRight');
    } else {
      lastRemovedCard.removeClass('toRight').addClass('card-front');
      currentCard.removeClass('card-front').addClass('card-middle');
      middleCard.removeClass('card-middle').addClass('card-back');
    }
    // backCard.removeClass('card-back').addClass('card-out');
    $('.btn-next').prop('disabled', true);

    try {
      $('#spinner-template-item').prop('hidden', false);
      $('#table-wrapper').prop('hidden', true);
      $('#table-marketlist').DataTable().destroy();
      $('#template-content-load').html('');
    } catch(error){
      console.log("Error on destroying datatable ", error);
    }

    try {
      $('#marketlist-template > .modal-dialog').removeClass('modal-lg');
      $('input[name="template-selection"]').each(function(){
        this.checked = false;
      });
      data_additional_item = [];
    } catch(error){
      console.log("Error when resetting state ", error)
    }

    updateNav();
    if(!hide)
      getTemplate();
  }

  function getTemplate(){
    try {
          $('#template-content-load').html('');
          $('#spinner-template').prop('hidden', false);
          $('#spinner-template-failed').prop('hidden', true);
      } catch(error){}

      try {
        var emp_id = $('input[name="Requestor_Employee_ID"]').val() || 0;
        $.ajax({
            url: '/finance/purchase-requisition-marketlist/request',
            method: 'GET',
            data: {'type':'recipe-template', 'employee_id': emp_id},
            dataType: 'json',
            success : function(response){
                if(response.hasOwnProperty('data') && response.data.length > 0){
                    is_error_template = false;
                    var element_to_add = '';
                    var col = 'col-4 col-md-4';
                    for(var loop=0;loop<response.data.length;loop++){
                        element_to_add += `<div class="switch-field ${col}">
                            <input type="radio" id="radio-${loop+1}" name="template-selection" value="${response.data[loop]}"/>
                            <label for="radio-${loop+1}">${response.data[loop]}</label>
                        </div>`;
                    }
                    $(element_to_add).appendTo('#template-content-load');
                }
            },
            error: function(xhr){
                $('#spinner-template-failed').prop('hidden', false);
                is_error_template = true;
            },
            complete: function(){
                $('#spinner-template').prop('hidden', true);
                setTimeout(function(){
                    if(!is_error_template)
                        $('.btn-next').prop('disabled', false);
                }, 500)
            }
        })
      } catch(error){
        console.log('Error in getTemplate function', error);
      }
  }

  $('.btn-retry-template').on('click', function(){
    getTemplate();
  });

  nextBtn.on('click', function(){
    showNextCard();
  });

  backBtn.on('click', function(){
    showPreviousCard(false);
  })

  $('#marketlist-template').on('hidden.bs.modal', function(){
    showPreviousCard();
  });

});