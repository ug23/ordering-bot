<?php

require 'vendor/autoload.php';

date_default_timezone_set('Asia/Tokyo');

$holidays = \Yasumi\Yasumi::create('Japan', date('Y'), 'ja_JP');

$datetime = new DateTime();
$day_of_week = date('N', $datetime->getTimestamp());
if ($day_of_week > 5 || $day_of_week < 1 || $holidays->isHoliday($datetime)) {
  echo "today is holiday.";
  return;
}

$webhook_url = getenv('WEBHOOK_URL');
$members = explode(',', getenv('MEMBERS'));
shuffle($members);
$list = "```" . implode("\n", $members) . "```";

$query = [
  "username" => "Good & Newのお時間です",
  "text" => $list,
  "icon_emoji" => ":city_sunset:"
];

$payload = ['payload' => json_encode($query)];

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $webhook_url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => http_build_query($payload),
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/x-www-form-urlencoded"
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
