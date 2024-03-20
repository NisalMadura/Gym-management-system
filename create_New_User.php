
 
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Power world fitness center</title>
  <link href="assets/css/bootstrap.css" rel="stylesheet">
  
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <script>
    $(document).ready(function() {
      <?php if (isset($_SESSION['message_suc']) && isset($_SESSION['alert_type'])): ?>
        Swal.fire({
          icon: '<?php echo $_SESSION['alert_type']; ?>',
          title: 'Registration',
          text: '<?php echo $_SESSION['message_suc']; ?>'
        }).then(function() {
          <?php 
            
            unset($_SESSION['message_suc']);
            unset($_SESSION['alert_type']);
          ?>
        });
      <?php endif; ?>
    });
  </script>



  
       
  

</head>

<body id="page-top" style="background-color: #D3D3D3;">


<div class="container-fluid">
    <div class="row justify-content-center mt-5">
  
  <div class="content-wrapper">
  
    <div class="container-fluid">
        <ol class="breadcrumb">

        <li class="breadcrumb-item active">Log In   </li>
        <li class="breadcrumb-item active">Create New User  </li>
        <div align="right">
		 	
</div>
		
		
      </ol>

      <div class="card-header">Register an Account  </div>
      <div class="card-body"  >
        <form   enctype="multipart/form-data" action="user_registration.php" method="post" >
          
            
          <div class="form-group ">
              
         
            
		       <div class="form-row ">
						 <div class="col-md-2">
                <label>Title</label>
					<select name="title"  class="form-control" >
						<option value="Mr">Mr</option>
						<option value="Ms">Ms</option>
				</select>	              
              </div>
			
              <div class="col-md-5">
                <label>First name  <Span style="color:red;">*</Span> <Span id='frist'style="color:red;"></Span></label>
                <input class="form-control" name="firstName" id='firstName' type="text" placeholder="Enter first name" >
               
              
              
              </div>
              <div class="col-md-5">
                <label>Last name <Span style="color:red;">*</Span> <Span id='last'style="color:red;"></Span></label>
                <input class="form-control" name="lastName" id='lastName'  type="text" placeholder="Enter last name" >
		              		
              </div>
            </div>
          </div>
		<div class="form-group">
				<div class="form-row ">
					<div class="col-md-6">
						<label >User Name </label>  <Span style="color:red;">*</Span>    <span id="usmsg" style="color:red;"></span>   <span id="availability"></span> 
						<input class="form-control" name="UserName1" type="text" id="username"  placeholder="Enter Pay code / Vendor Code" >
					</div>
			  	<div class="col-md-6">
						<label >ID Number </label>  <Span style="color:red;">*</Span>    <span id="usmsg" style="color:red;"></span>   <span id="availability"></span> 
						<input class="form-control" name="idnumber" type="text" id="idnumber"  placeholder="Enter ID Number" >
					</div>
                                       
					
					
				</div>
		  </div>	  
		  
		<div class="form-group">
		  
		  <div class="form-row">
				<div class="col-md-7">
                                    <label>Email Address</label> <Span style="color:red;">*</Span> <span id="emailmsg" style="color:red" ></span> 
                                    <input class="form-control" id="email" name="email" type="email" placeholder="Enter email" required>
				</div>
			<div class="col-md-5">
					<label>Contact Number</label> <Span style="color:red;">*</Span>  <span id="conmsg" style="color:red" ></span>
                                        <input class="form-control" id="contactNo"  name="contactNo"   type="text" placeholder="Enter contact Number" > 
				</div> 
			  
                      
                      
                      
			
           </div>
		  
		</div>
		  
		 
		  
		  
		  
                
                 
			  
			 
           
            
  		   
          <div class="form-group">
		 
		  
            <div class="form-row">
              <div class="col-md-6">
             <label >Password</label>  
                <input class="form-control" name="password_1" type="password" id="pass1" placeholder="Password" required>

              </div>

              
              <div class="col-md-5">
             <label >Role</label>  
                <input class="form-control" name="Role" type="Role" id="Role" placeholder="Role" required>

              </div>
            </div>

         </div>
          

         </div>
         </div>    
         <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-3 offset-md-3">
                                &nbsp;&nbsp;&nbsp;     <button class="btn btn-primary" onclick="goBack()">Back</button>
                                </div>
                                <div class="col-md-3 text-right">
                                &nbsp;&nbsp;&nbsp;  <button type="submit" name="submit" class="btn btn-primary" id="register" onclick="return checkValidations();">Register</button>
                                </div>
                            </div>
                        </div>
         </div>
         <center>

<script>
function goBack() {
  window.history.back();
}




</script>		

		
</center>
		 

        
        
       
		  
</div></div>

            
            

            
            
            
            
        </form>
		 
        </div>

    </div>
	 
    
	

<script>  
   $('#username').blur(function(){

     var username = $(this).val();

	if(username != ""){
     $.ajax({
      url:'check.php',
      method:"POST",
      data:{UserName1:username},
      success:function(data)
      {
       if(data != '0')
       {
        $('#availability').html('<span class="text-danger">Paycode is already exist</span>');
        $('#register').attr("disabled", true);
       }
       else
       {
        $('#availability').html('<span class="text-success">Paycode is not Available</span>');
        $('#register').attr("disabled", false);
       }
      }
     })
	} else {
		$('#availability').html('');
		$('#availability').html('');
		$('#register').attr("disabled", true);
	}

  });
 
 
 
</script>

<script> 

function checkValidations()
{
   
            var f =document.getElementById("fristName").value ;
        if(f=="")
          {``
          document.getElementById("frist").innerHTML="Frist Name is empty";
          
          }  
        else
          document.getElementById("frist").innerHTML="";
    
    
    
       var l =document.getElementById("lastName").value ;
            
          if(l=="")
          {
          document.getElementById("last").innerHTML="Last Name is empty";
         
          }   
         else
          document.getElementById("last").innerHTML="";
    
    
    
    
       
      
    
          var em =document.getElementById("email").value ;
            
          if(em=="")
          {
          document.getElementById("emailmsg").innerHTML="Email is empty";
         
          }   
         else
          document.getElementById("emailmsg").innerHTML="";
    
    
    
    
           var us =document.getElementById("username").value ;
            
          if(us=="")
          {
          document.getElementById("usmsg").innerHTML="User Name is empty";
         
          }   
         else
          document.getElementById("usmsg").innerHTML="";
    
    
        var ab =document.getElementById("contactNo").value ;
        if(ab=="")
          {
          document.getElementById("conmsg").innerHTML="contact number is empty";
           
        }
         else if(isNaN(ab))
        {
          document.getElementById("conmsg").innerHTML="Enter valid contact number";
          return false ;
        } 
        
        else if(ab.length==10 && ab.charAt(0)==0 )  {
              document.getElementById("conmsg").innerHTML="";
          return true ;
        }
        
        else if(ab.length==12 && ab.charAt(0)=='+' )  {
            document.getElementById("conmsg").innerHTML="";
           return true ;
        }
        else{ 
          document.getElementById("conmsg").innerHTML="Enter valid contact number";
          return false ;
      }      
       
    
    
    
    
	     
}  
	
</script>

	
	
	
    

</body>

</html>

 