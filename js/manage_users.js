$(document).ready(function(){
  // Add user
  $(document).on('click', '.user_add', function(){
    //user Info
    var name = $('#name').val();
    var idcardnumber = $('#idcardnumber').val();
    var address = $('#address').val();
    var birthday = $('#birthday').val();
    var gender = $('#gender').val();
    var phonenumber = $('#phonenumber').val();
    var whatsappno= $('#whatsappno').val();
    var userheight = $('#userheight').val();
    var userweight = $('#userweight').val();
    var registerdate = $('#registerdate').val();
    var memberno = $('#memberno').val();

        

        // Additional validation for phone number and id card number
       /* if (!/^(\d{9}[Vv]|[0-9]{12})$/.test(idcardnumber)) {
           alert('ID card number should be 9 digits followed by V or v, or 12 digits');
           return;
        }*/



        if (!/^\d{10}$/.test(phonenumber)) {
            alert('Phone number should be 10 digits');
            return;
        }

    $.ajax({
      url: 'manage_users_conf.php',
      type: 'POST',
      data: {
        'Add': 1,
        'name': name,
        'idcardnumber': idcardnumber,
        'address': address,
        'birthday': birthday,
        'gender': gender,
        'phonenumber': phonenumber,
        'whatsappno': whatsappno,
	'userheight': userheight,
	'userweight': userweight,
	'registerdate': registerdate,
        'memberno':memberno,
		
		
      },
      success: function(response){
        $('#name').val('');
        $('#registerdate').val('');
        $('#address').val('');

        $('#birthday').val('');
        $('#gender').val('');
        $('#phonenumber').val('');
        $('#whatsappno').val('');
        $('#userheight').val('');
        $('#userweight').val('');
        $('#registerdate').val('');
        $('#memberno').val('');

        $('#alert').show();
        $('#alert').text(response);
        $.ajax({
          url: "manage_users_up.php"
          }).done(function(data) {
          $('#manage_users').html(data);
        });
      }
    });
  });
  // Add user Fingerprint
  $(document).on('click', '.fingerid_add', function(){

    var fingerid = $('#fingerid').val();;
    
    $.ajax({
      url: 'manage_users_conf.php',
      type: 'POST',
      data: {
        'Add_fingerID': 1,
        'fingerid': fingerid,
      },
      success: function(response){
        $('#fingerid').val('');
        
        $('#alert').show();
        $('#alert').text(response);
        $.ajax({
          url: "manage_users_up.php"
          }).done(function(data) {
          $('#manage_users').html(data);
        });
      }
    });
  });
  // Update user
  $(document).on('click', '.user_upd', function(){
    //user Info
    var name = $('#name').val();
    var idcardnumber = $('#idcardnumber').val();
    var address = $('#address').val();
    var birthday = $('#birthday').val();
    var gender = $('#gender').val();
    var phonenumber = $('#phonenumber').val();
    var whatsappno = $('#whatsappno').val();
    var userheight = $('#userheight').val();
    var userweight = $('#userweight').val();
    var registerdate = $('#registerdate').val();
    var memberno= $('#memberno').val();

    $.ajax({
      url: 'manage_users_conf.php',
      type: 'POST',
      data: {
        'Update': 1,
        'name': name,
        'idcardnumber': idcardnumber,
        'address': address,
        'birthday': birthday,
        'gender': gender,
        'phonenumber': phonenumber,
	'userheight': userheight,
	'userweight': userweight,
	'registerdate': registerdate,
        'memberno': memberno,
 
      },
      success: function(response){
        $('#name').val('');
        $('#registerdate').val('');
        $('#address').val('');

        $('#birthday').val('');
        $('#gender').val('');
        $('#phonenumber').val('');
        $('#whatsappno').val('');
        $('#userheight').val('');
        $('#userweight').val('');
        $('#registerdate').val('');
        $('#memberno').val('');

        $('#alert').show();
        $('#alert').text(response);
        
        $.ajax({
          url: "manage_users_up.php"
          }).done(function(data) {
          $('#manage_users').html(data);
        });
      }
    });   
  });
  // delete user
  $(document).on('click', '.user_rmo', function(){
  	$.ajax({
  	  url: 'manage_users_conf.php',
  	  type: 'POST',
  	  data: {
    	'delete': 1,
      },
      success: function(response){
         $('#name').val('');
        $('#registerdate').val('');
        $('#address').val('');

        $('#birthday').val('');
        $('#gender').val('');
        $('#phonenumber').val('');
        $('#whatsappno').val('');
        $('#userheight').val('');
        $('#userweight').val('');
        $('#registerdate').val('');
        $('#memberno').val('');
        $('#alert').show();
        $('#alert').text(response);
        $.ajax({
          url: "manage_users_up.php"
          }).done(function(data) {
          $('#manage_users').html(data);
        });
      }
  	});
  });
  // select user
  $(document).on('click', '.select_btn', function(){
    var Finger_id = $(this).attr("id");
    $.ajax({
      url: 'manage_users_conf.php',
      type: 'GET',
      data: {
      'select': 1,
      'Finger_id': Finger_id,
      },
      success: function(response){

        $('#alert').show();
        $('#alert').text(response);

        $.ajax({
          url: "manage_users_up.php"
          }).done(function(data) {
          $('#manage_users').html(data);
        });
      }
    });
  });
});