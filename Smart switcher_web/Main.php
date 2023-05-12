<?php
  $Write="<?php $" . "getLEDStatusFromNodeMCU=''; " . "echo $" . "getLEDStatusFromNodeMCU;" . " ?>";
  file_put_contents('LEDStatContainer.php',$Write);
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
        $("#getLEDStatus").load("LEDStatContainer.php");
        setInterval(function() {
          $("#getLEDStatus").load("LEDStatContainer.php");
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

    </style>
  </head>
  <body>
    <h1>Relay01S_Web Server</h1>
    <p nowarp>Now is</p>
    <p_on id="on"></p_on>
    <p_off id="off"></p_off>
    
    <form action="updateDBLED.php" method="post" id="LED_ON" onsubmit="return ajaxpost()">
      <input type="hidden" name="Stat" value="1"/>    
    </form>
    
    <form action="updateDBLED.php" method="post" id="LED_OFF" onsubmit="return fetchpost()">
      <input type="hidden" name="Stat" value="0"/>
    </form>
    
    <button id="on" onclick="ON()" class="buttonON" name= "subject" type="submit" form="LED_ON" value="SubmitLEDON" >LED ON</button>
    <button id="off" onclick="OFF()" class="buttonOFF" name= "subject" type="submit" form="LED_OFF" value="SubmitLEDOFF">LED OFF</button>  
    <h2 id="ledstatus" style="color:#6f4a8e;">LED Status = </h2>
    <p id="getLEDStatus" hidden></p>
  
    <script>
    var myVar = setInterval(myTimer, 500);
    function myTimer() {
      var getLEDStat = document.getElementById("getLEDStatus").innerHTML;
      var LEDStatus = getLEDStat;
      if (LEDStatus == 1) {
        document.getElementById("ledstatus").innerHTML = "LED Status = ON";
      }
      if (LEDStatus == 0) {
        document.getElementById("ledstatus").innerHTML = "LED Status = OFF";
      }
      if (LEDStatus == "") {
        document.getElementById("ledstatus").innerHTML = "LED Status = Waiting for the Status LED from NodeMCU...";
      }
    }
    </script> 
  </body>
</html>