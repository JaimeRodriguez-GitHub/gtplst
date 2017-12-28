<html><head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta name="robots" content="noindex, nofollow">
  <meta name="googlebot" content="noindex, nofollow">

  
  

  
  
  

  

  <script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>

  

  

  

  
    <link rel="stylesheet" type="text/css" href="/css/result-light.css">
  

  
    
      <link rel="stylesheet" type="text/css" href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css">
    
  

  <style type="text/css">
    
  </style>

  <title> by natchiketa</title>

  
    




<script type="text/javascript">//<![CDATA[
$(window).load(function(){
$('#without-step').on('change keyup', function(event) {
    if ( event.target.validity.valid ) {
        $('#test1').text($(this).val());
    } else {
        $('#test1').text('NOPE');
    }
});

$('#with-step').on('change keyup', function(event) {
    if ( event.target.validity.valid ) {
        $('#test2').text($(this).val());
    } else {
        $('#test2').text('NOPE');
    }    
});
});//]]> 

</script>

  
</head>

<body>
  <div class="container">
    <label for="without-step">Without Step Attribute</label>
    <input type="number" pattern="[0-9]+([\,|\.][0-9]+)?" name="my-num" title="The number input must start with a number and use either comma or a dot as a decimal character." id="without-step">
<div id="test1"></div>    
    <label for="with-step">With a step attribute set to 0.01</label>
<input type="number" pattern="[0-9]+([\,|\.][0-9]+)?" name="my-num" step="0.01" title="The number input must start with a number and use either comma or a dot as a decimal character." id="with-step">
    <div id="test2">2</div>
</div>


  




</body></html>