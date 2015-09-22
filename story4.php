<?php
// working on scaling. should scale both across results and maybe have a variable that checks percentages? so maybe each new coord checks for percentage of change, rather than exact change
// so, if in this case, the total change of the x value is 700%, so it could compare each x value to its original position. looking for 1% incriments, rather than incriments of 1?
// ok but first, how can I make it scalable? so I need to check for this same pattern across 8, 80, and 800 entries. It could just skip in multiples of ten? so, if the 20th coord matches the 2nd from the gesture, within a variable, then they are a match? maybe the check everything in between the two for major changes. 
$db = new SQLite3('test.db');

$jump = array
	( 
	array(1,30,211), 
	array(-7,34,203),
	array(-20,29,205),
	array(-21,24,193),
	array(-18,21,205),
	array(-49,21,199),
	array(-46,27,191),
	array(-44,29,193),
	array(-56,26,196),
	array(-61,24,191),
	array(-53,13,216),
	array(-44,19,154),
	array(-47,23,131),
	array(-60,21,185),
	array(9,36,184),
	array(-25,10,223),
	array(-18,12,198),
	array(7,22,211),
	array(41,29,213),
	array(39,25,215),
	array(35,28,194),
	array(44,30,225),
	array(31,16,221),
	array(14,9,221),
	array(3,20,238),
	array(14,0,198),
	array(-1,6,201),
	array(0,9,204)
	);

$shake = array
	( 
	array(-65,12,273), 
	array(330,148,90),
	array(-318,-63,301),
	array(-296,-67,202),
	array(511,157,142),
	array(-298,-66,266),
	array(20,27,62),
	array(511,145,233),
	array(-512,-78,295),
	array(-345,-73,17),
	array(511,153,140),
	array(-512,-99,291),
	array(-115,-11,-78),
	array(511,61,245),
	array(-463,-62,286),
	array(-185,12,-108),
	array(511,58,367),
	array(-332,-34,190),
	array(-166,-3,-70),
	array(149,33,448),
	array(268,54,278),
	array(-512,-78,128),
	array(409,97,97),
	array(197,30,468),
	array(-430,-37,-37),
	array(325,65,55),
	array(138,-16,313),
	array(-328,-63,122),
	array(511,162,50),
	array(-270,-65,341),
	array(-316,-53,366),
	array(65,32,164),
	array(8,17,197)
	);
	
$dance = array
	( 
	array(6,5,202), 
	array(1,6,201),
	array(-1,10,211),
	array(5,19,207),
	array(5,11,197),
	array(8,17,189),
	array(33,17,177),
	array(50,17,244),
	array(-9,13,280),
	array(-28,-13,170),
	array(-13,14,110),
	array(2,20,270),
	array(6,34,139),
	array(20,37,239),
	array(0,31,178),
	array(-10,22,116),
	array(-19,-15,297),
	array(32,13,276),
	array(42,19,195),
	array(26,29,111),
	array(18,26,282),
	array(2,29,146),
	array(7,7,223)
	);

$slide = array
	( 
	array(1,54,211), 
	array(6,48,197),
	array(7,55,195),
	array(8,51,196),
	array(9,52,204),
	array(12,47,197),
	array(16,43,202),
	array(20,37,196),
	array(20,47,195)
	);

$sit = array
	(
	array(-2, 32, 200),
	array(-3, 32, 201),
	array(-4, 32, 203), 
	array(-3, 33, 201), 
	array(-4, 33, 201), 
	array(-3, 32, 202), 
	array(-2, 32, 201), 
	array(-4, 32, 202), 
	array(-3, 32, 202), 
	array(-3, 32, 201), 
	array(-3, 32, 201), 
	array(-3, 33, 201), 
	array(-2, 33, 201), 
	array(-3, 33, 203), 
	array(-4, 33, 202), 
	array(-3, 33, 202), 
	array(-2, 32, 203), 
	array(-3, 33, 201), 
	array(-3, 33, 202), 
	array(-4, 31, 201), 
	array(-2, 33, 201), 
	array(-3, 33, 201), 
	array(-4, 33, 201), 
	array(-4, 32, 201), 
	array(-3, 31, 201), 
	array(-3, 32, 201), 
	array(-2, 34, 200), 
	array(-1, 32, 202)
	);
	
$sleep = array
	(
	array(-9, 34, 200),
	array(-2, 30, 197),
	array(-2, 33, 204),
	array(-3, 34, 202),
	array(-2, 34, 202),
	array(-2, 32, 202),
	array(-2, 33, 201),
	array(0, 40, 201),
	array(-2, 31, 201),
	array(-8, 49, 196),
	array(-12, 53, 205),
	array(-6, 91, 191),
	array(-1, 108, 178),
	array(10, 160, 130),
	array(8, 168, 123),
	array(0, 186, 125),
	array(-2, 192, 134),
	array(-7, 205, 111),
	array(-4, 231, 79),
	array(-4, 243, 68),
	array(-5, 254, 49),
	array(-7, 247, 0),
	array(-11, 268, -9),
	array( 0, 274, -64),
	array(-2, 273, -66),
	array(-3, 266, -58),
	array(-2, 269, -62),
	array(-2, 269, -63),
	array(-1, 268, -61),
	array(-1, 269, -65),
	array(-1, 269, -65),
	array(-1, 268, -68),
	array(-1, 268, -68),
	array(-1, 268, -68),
	array(-2, 269, -69),
	array(-1, 269, -72),
	array(-2, 269, -69),
	array(-2, 269, -70),
	array(-1, 266, -68),
	array(-1, 268, -75),
	array(-2, 269, -71),
	array(-1, 268, -70),
	array(-1, 268, -71),
	array(-1, 270, -71),
	array(-1, 267, -71),
	array(-1, 269, -71),
	array(-2, 268, -70),
	array(-1, 267, -70),
	array(-1, 267, -70),
	array(-1, 268, -72),
	array(-1, 268, -70),
	array(-1, 266, -70),
	array(-1, 271, -76),
	array(-1, 271, -70),
	array(-1, 268, -64),
	array(-2, 268, -64),
	array( 0, 269, -64),
	array(-1, 268, -65),
	array(-1, 269, -66),
	array(-3, 263, -51),
	array(-3, 270, -58),
	array(-5, 271, -62),
	array(-7, 255, -64),
	array(-4, 266, -65)
	);

$gestures = array($jump,$shake,$dance,$slide,$sit,$sleep);
$names = array("jump","shake","dance","slide","sit","sleep");
// I need the threshold to be proportionate. In this case, 10 is pretty arbitrary (and happens to include all the values). If io was sitting still, this would test positive, so maybe calculate percentage changes from the original gesture array and use those as the threshold? A percentage of this percentage could be the actual threshold. So, if 7 is a 7 point change, that's 700% of 1. So, if the threshold is 100% of the total change, the threshold would equal 1 point. 
$threshold = 10;
$results = $db->query('SELECT * FROM yay');
$verbs = array();
$matchKeys = array();
$match = false;
$subject = "Io";
$verbs = array();
$nouns = array(
	"see" => array("a book", "water", "a bird"),
	"eat" => array("dinner", "an apple", "a banana"),
	"ride" => array("a bike", "a horse", "a train", "a plane", "a bus"),
	"play" => array(""),
	"find" => array("a spider","a sock","a penny","a worm"),
	"jump" => array(""),
	"shake" => array(""),
	"slide" => array("","across the floor"),
	"walk" => array("","home","to the park","to school", "to his room"),
	"sit" => array("","down"),
	"dance" => array("")
	);
// $refNouns = array(
// 	"see" => array("the book","the water", "the bird"),
// 	"eat" => array("the apple", "the banana", "the broccoli", "the crackers"),
// 	"jump" => array("up","over the stick","over the fence","over the snake","over the water","on the bed"),
// 	"shake" => array("the drink","the penny","the sock"),
// 	"cross" => array("the water","the field","the street","the room"),
// 	"walk" => array("to the field"),
// 	"sit" => array("in the grass","on the bed","at the table","near the rock")
// 	);

for ($i=0;$i<count($gestures);$i++){
	checkValues($gestures[$i],$names[$i]);
}

foreach ($verbs as $verb){
	writeStory($verb);
}

function writeStory($verb){
	global $subject, $nouns;
	$c = count($nouns[$verb]);
	$noun = rand(0,$c);
	$s = $subject . " " . $verb . "s " . $nouns[$verb][$noun] . ".  ";
	echo $s;
}

function checkValues($gesture,$name){
	global $results, $match, $matchKeys, $verbs;
	$xyzArray = array();
	while ($row = $results->fetchArray()) {
		$x = $row[0];
		$y = $row[1];
		$z = $row[2];
		array_push($xyzArray, array($x,$y,$z));
		}
		$myResults = keyMatches($gesture,$xyzArray);
		if ($myResults[0]){
			$res = array_slice($verbs, 0, $myResults[1]-1, true) + array($myResults[1] => $name) + array_slice($verbs, $myResults[1]-1, count($verbs)-$myResults[1]-1, true);
			$verbs = $res;
		} 
	}

function keyMatches($gesture,$xyzArray) {
	global $matchKeys, $threshold;
	$m = 0;
	for ($x=0; $x < count($xyzArray); $x++){
		// get the first value from each gesture
		$xG = $gesture[0][0];
		$yG = $gesture[0][1];
		$zG = $gesture[0][2];
		// compare the x,y,z values for each movement in xyzArray to the x,y,z values from the first array in the gesture
		$xR = abs($xyzArray[$x][0] - $xG);
		$yR = abs($xyzArray[$x][1] - $yG);
		$zR = abs($xyzArray[$x][2] - $zG);
		//check the individual x,y,z values against our threshold
		if ($xR < $threshold){
			$bX = true;
			}
		if ($yR < $threshold){
			$bY = true;
			}
		if ($zR < $threshold){
			$bZ = true;
			}
		//if they are all true, look for consecutive matches. 	
		if ($bX && $bY && $bZ){
			for ($i = 0; $i < count($gesture); $i++){
				// separate the x,yz values in gesture so I can check them against the threshold
				$xA = $gesture[$i][0];
				$yA = $gesture[$i][1];
				$zA = $gesture[$i][2];
				 // compare the xyz values from gesture to the xyz values from xyzArray
				$xV = abs($xyzArray[$x+$i][0] - $xA);
				$yV = abs($xyzArray[$x+$i][1] - $yA);
				$zV = abs($xyzArray[$x+$i][2] - $zA);
				// check the result against the threshold.
				if ($xV < $threshold){
					$xTest = true;
				}
				if ($yV < $threshold){
					$yTest = true;
				}
				if ($zV < $threshold){
					$zTest = true;
				}
				// if they are all true, incriment m and add the key from the matching xyz segment to matchKeys.
				if ($xTest && $yTest && $zTest){
					// this needs to check to make sure gestures don't overlap.
					$m++;
					$aN = $x+$i;
					array_push($matchKeys,$aN);
					// if we have reached the end of our gesture, and all match, then we have a total match.
					if ($m == count($gesture)){
						$match = true;
						$matchResults = array($match,$aN);
						return $matchResults;
					} 
				}
			}
		}
	}
}
	

// deeper AI stuff, next step after prototype.

// function getThreshold(){
// // 	ok just realized I don't want the same threshold applied to all 3 axes.
// 	$p = $gesture[0][0];
// 	$n = count($gesture)-1;
// 	$g = $gesture[$n][0];
// 	$changePoints = $g - $p;
// }
// 
// function expandArray($e){
// // 	this expands the array? How much should it be expanded?
// 	for ($i = 0; $i < count($gesture); $i++){
// 		for ($n = 0; $n < $e; $n++){
// 			array_push($newGesture,$gesture[$i]);
// 			}
// 		}
// 	}

