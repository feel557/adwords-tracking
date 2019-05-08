<!DOCTYPE html>
<html lang="en-US">
<head>
<meta charset="utf-8">
<style>

p {
padding:0px 0;
}

h2{
font-family: Arial, sans-serif;
font-size: 18px;
font-weight: normal;
color: #428bca;
margin-bottom: 10px;
text-transform: uppercase;
}

</style>
</head>
<body>
<h2><? if(isset($data["subject"])){ echo $data["subject"]; } ?></h2>
<div>
<? if(isset($data["text"])){ echo $data["text"]; } ?>
</div>
</body>
</html>