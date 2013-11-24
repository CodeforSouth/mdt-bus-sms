<?
$name = "citizen";

// now greet the caller
header("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>

<Response>
<Say voice="woman">Hello <?php echo $name; ?>, Text My Bus MIA is an SMS system that enables the user to text the ID number of his or her bus stop and receive a message with upcoming bus times for that stop. The goal is to provide easy access to updated bus schedules for low-income individuals and other Miami-Dade County residents and visitors who rely on public transportation for commuting and getting around the county. For more information visit http://www.codeformiami.org.</Say>
</Response>
