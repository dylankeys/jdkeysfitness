$('#bookingModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var booking = button.data('booking') // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this)
    modal.find('.modal-body input.booking').val(booking)
  })

var counter = 1;
var dynamicInput = [];
  
function addInput(){
  var newdiv = document.createElement('div');
  dynamicInput[counter] = 'dynamicInput['+counter+']';
  newdiv.id = dynamicInput[counter];
  newdiv.innerHTML = "<br><div class='row'><div class='col'><input class='form-control' type='datetime-local' name='sessions[]'></div><div class='col'><button type='button' class='btn btn-primary' onClick='removeInput("+dynamicInput[counter]+");'>-</button></div></div>";
  document.getElementById('session-inputs').appendChild(newdiv);
  counter++;
}
    
function removeInput(id){
  var elem = document.getElementById(id);
  return elem.parentNode.removeChild(elem);
}