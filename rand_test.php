<?php
echo "mt_randテスト"."<br>";

$min = 0;
$max = 2;
$count1 = 0;
$count2 = 0;
$loop = 1000000;

for($i=0; $i < $loop; $i++)
if(mt_rand($min,$max)) ++$count1; 
else ++$count2;

echo "count1:".floor(($count1/$loop)*100). "%   "  . "<br>";
echo "count2:".floor(($count2/$loop)*100). "%   "  . "<br>";

for($i=0; $i < 100; $i++) echo mt_rand($min,$max)."<br>";

// 0でif文がfalseになるから、0になる確率をベースに決めればいい

?>