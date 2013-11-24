<?
$name = "citizen";

// now greet the caller
header("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>

<Response>
<Say voice="woman">Hello <?php echo $name ?>, thank you for calling the text my bus, help line. I will be happy to assist you!.</Say>
</Response>