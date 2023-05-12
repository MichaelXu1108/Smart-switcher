<?php
if (!isset($_SERVER ['PHP_AUTH_USER'])) {
  header('WWW-Authenticate: Basic realm= "Private Area"');
  header("HTTP/1.0 401 Unauthorized");
  echo '<script>window.location.close();</script>';
  exit;
  }
else{
  if (($_SERVER['PHP_AUTH_USER'] == "yjo494512" && ($_SERVER['PHP_AUTH_PW'] == "851108"))){
    ;
  } else {
    header("WWW-Authenticate: Basic realm= 'Private Area'");
    header("HTTP/1.0 401 Unauthorized");
    exit;
  }
}
?>

<?php
  $Write="<?php $" . "getLEDStatusFromNodeMCU=''; " . "echo $" . "getLEDStatusFromNodeMCU;" . " ?>";
  file_put_contents('data/LEDStatContainer.php',$Write);
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Relay01S-Websever</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <script src="jquery.min.js"></script>
    <script>
      $(document).ready(function(){
        $("#getLEDStatus").load("data/LEDStatContainer.php");
        setInterval(function() {
          $("#getLEDStatus").load("data/LEDStatContainer.php");
        }, 500);
      });
    </script>
    
    <script>
      function ajaxpost () {
      // (A) GET FORM DATA
      var form = document.getElementById("LED_ON");
      var data = new FormData(form);
    
      // (B) AJAX
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "updateDBLED.php");
      // What to do when server responds
      xhr.onload = function () { console.log(this.response); };
      xhr.send(data);
    
      // (C) PREVENT HTML FORM SUBMIT
      return false;
    }
      function fetchpost () {
          // (A) GET FORM DATA
          var form = document.getElementById("LED_OFF");
          var data = new FormData(form);

          // (B) FETCH
          fetch("updateDBLED.php", {
            method: "post",
            body: data
          })
          .then((res) => { return res.text(); })
          .then((txt) => { console.log(txt); })
          .catch((err) => { console.log(err); });

          // (C) PREVENT HTML FORM SUBMIT
          return false;
        }
      function ON() { 
        document.getElementById("on").innerHTML = "ON";
        document.getElementById("off").innerHTML = "";
      }
      function OFF() { 
        document.getElementById("off").innerHTML = "OFF";
        document.getElementById("on").innerHTML = "";
      }

      var form = document.getElementById("myForm"); function handleForm(event) {     event.preventDefault(); }  form.addEventListener('submit', handleForm);
    </script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      html {
          font-family: Arial;
          display: inline-block;
          margin: 0px auto;
          text-align: center;
      }
      
      h1 { color: #0F3376;  padding: 2vh;}
      p { font-size: 1.5rem;}
      p_on {font-size: 1.5rem; color: #1aa456;}
      p_off {font-size: 1.5rem; color: #bd1d32; }
      p_return {font-size: 1.2rem; color: #404040; }
      img[src*="https://cdn.000webhost.com/000webhost/logo/footer-powered-by-000webhost-white2.png"] { display: none;}
    </style>
  </head>
  <body>
    <h1>Relay01S_Web Server</h1>
    <p nowarp>Change to</p>
    <p_on id="on"></p_on>
    <p_off id="off"></p_off>
    <br>
    <br>
    <form action="updateDBLED.php" method="post" id="LED_ON" onsubmit="return ajaxpost()">
      <input type="hidden" name="Stat" value="1"/>    
    </form>
    
    <form action="updateDBLED.php" method="post" id="LED_OFF" onsubmit="return fetchpost()">
      <input type="hidden" name="Stat" value="0"/>
    </form>
    
    <button id="on" onclick="ON()" class="btn btn-outline-success btn-lg" name= "subject" type="submit" form="LED_ON" value="SubmitLEDON" >ON</button>
    <button id="off" onclick="OFF()" class="btn btn-outline-danger btn-lg" name= "subject" type="submit" form="LED_OFF" value="SubmitLEDOFF">OFF</button>  
    <br>
    <br>
    <p nowarp>Now is</p>
    <p_return id="ledstatus"> Power = </p_return>
    <p_return id="getLEDStatus" hidden></p_return>
  
    <script>
    var myVar = setInterval(myTimer, 500);
    function myTimer() {
      var getLEDStat = document.getElementById("getLEDStatus").innerHTML;
      var LEDStatus = getLEDStat;
      if (LEDStatus == 1) {
        document.getElementById("ledstatus").innerHTML = "Power = ON";
      }
      if (LEDStatus == 0) {
        document.getElementById("ledstatus").innerHTML = "Power = OFF";
      }
      if (LEDStatus == "") {
        document.getElementById("ledstatus").innerHTML = "Power = Waiting for the Status from Relay01S...";
      }
    }
    </script> 

    

  </body>
</html>